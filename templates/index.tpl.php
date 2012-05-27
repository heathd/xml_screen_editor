<?php require $this->template('templates/header.tpl.php') ?>

<h1>Screens</h1>

<?php if ($this->flash): ?>
  <p class="successful"><?php $this->eprint($this->flash); ?></p>
<?php endif; ?>

<table>
<?php foreach ($this->screens as $screen): ?>
  <tr>
    <td><?= $this->escape($screen['screenID']) ?></td>
    <td>
      <a href='edit.php?screen_id=<?= $this->escape($screen['screenID']) ?>'>
        <?= $this->escape($screen->screenFirstName) ?>
      </a>
    </td>
    <td>
      <?= $this->escape($screen->screenTime) ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>

</body></html>
