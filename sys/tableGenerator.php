<?php ob_start(); ?>
    <table>
    <thead>
      <tr>
          <?php foreach ($tableTemplate as $v): ?>
            <td>$v</td>
          <?php enforeach; ?>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($tableContent as $v): ?>
      <tr>
        <?php foreach ($v as $val): ?>
        <td>$val</td>
        <?php enforeach; ?>
      </tr>
      <?php enforeach; ?>
    </tbody>
  </table>
<?php
  $content = ob_get_clean();
  include "topMenuContent.php";
?>
