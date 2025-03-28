<?php

namespace datenshi;

// Setup oauth2 uris for social login
$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';
$apiURLBase = 'https://discordapp.com/api/users/@me';

/**
 * Authentication component to be rewritten in the future
 */

use Exception;

class Auth {
  private static bool $isLogged = false;
  private static ?User $user = null;

  public static function auth(?string $authHeader, ?string $token): ?array {
      if (isset($_COOKIE["datenshi_at"]) && !empty($_COOKIE["datenshi_at"])) {
          return self::validateToken($_COOKIE["datenshi_at"]);
      }
      if ($authHeader) {
          return self::authenticateWithBasicAuth($authHeader);
      }
      return null;
  }

  public static function authenticateWithBasicAuth(string $authHeader): ?array {
    global $db;
      if (strpos($authHeader, 'Basic ') !== 0) {
          return null;
      }
      
      $decoded = base64_decode(substr($authHeader, 6));
      if (!$decoded || !str_contains($decoded, ':')) {
          return null;
      }
      
      [$email, $password] = explode(':', $decoded, 2);
      $user = self::$db->getLine("SELECT id, email, username FROM datenshi_users WHERE email = ?", [$email]);
      
      if ($user && password_verify($password, $user['password'])) {
          self::$isLogged = true;
          self::$user = new User($user['id'], $user['email'], $user['username']);
          return self::generateToken($user['id']);
      }
      
      return null;
  }

  public static function generateToken(int $userId): array {
    global $db;
      $token = bin2hex(random_bytes(32));
      self::$db->exec("INSERT INTO datenshi_tokens (user_id, token, created_at) VALUES (?, ?, NOW())", [$userId, $token]);
      return ['token' => $token, 'user_id' => $userId];
  }

  public static function validateToken(string $token): ?array {
    global $db;
      $userData = self::$db->getLine("SELECT u.id, u.email, u.username FROM datenshi_tokens t JOIN datenshi_users u ON t.user_id = u.id WHERE t.token = ?", [$token]);
      if ($userData) {
          self::$isLogged = true;
          self::$user = new User($userData['id'], $userData['email'], $userData['username']);
          return $userData;
      }
      return null;
  }

  public static function logout(string $token): bool {
    global $db;
      self::$isLogged = false;
      self::$user = null;
      return self::$db->exec("DELETE FROM datenshi_tokens WHERE token = ?", [$token]);
  }

  private static function apiRequest($url, $post=FALSE, $headers=array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    if($post)
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $headers[] = 'Accept: application/json';
    if(cookie('tm_token'))
      $headers[] = 'Authorization: Bearer ' . cookie('tm_token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    return json_decode($response);
  }
}

class User {
    public int $id;
    public string $email;
    public string $username;

    public function __construct(int $id, string $email, string $username) {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
    }
}

?>
