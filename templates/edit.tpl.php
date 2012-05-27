<?php require $this->template('templates/header.tpl.php') ?>

<h1><?php $this->eprint($this->page_title) ?></h1>

<?php if ($this->runtime_error): ?>
  <p class="error"><?php $this->eprint($this->runtime_error); ?></p>
<?php endif; ?>


<form method="post">
  <input type="hidden" name="screen_id" value="<?php $this->eprint($_GET['screen_id']) ?>">

  <input type="submit" value="Save changes">
  <a href="index.php">Cancel</a>

  <p><label>
    Screen name
    <input type="text" name="screenFirstName" value="<?php $this->eprint($this->screen->screenFirstName) ?>" >
  </label></p>

  <p><label>
    Screen time
    <input type="text" name="screenTime" value="<?php $this->eprint($this->screen->screenTime) ?>" >
  </label></p>

  <?php $i=0; ?>
  <?php foreach ($this->screen->boxs->box as $box): ?>
    <fieldset>
      <legend>Box <?= $i + 1 ?></legend>
      <?php $boxContentNodeName = $box->box4Main ? "box4Main" : "box2User" ?>
      <input type="hidden" name="box[<?= $i ?>][id]" value="<?php $this->eprint($box['boxID']) ?>" >
      <input type="hidden" name="box[<?= $i ?>][type]" value="<?= $boxContentNodeName ?>" >
      <p>
      <label>Content<br />
        <textarea name="box[<?= $i ?>][content]" rows="10" cols="80"><?php 
          $this->eprint($box->$boxContentNodeName) 
        ?></textarea>
      </label>
      </p>

      <p>
        <label>Variable one:
          <input type="text" name="box[<?= $i ?>][boxVariableOne]" value="<?php $this->eprint($box->boxVariableOne) ?>" >
        </label>
      </p>

      <p>
        <label>Variable two:
          <input type="text" name="box[<?= $i ?>][boxVariableTwo]" value="<?php $this->eprint($box->boxVariableTwo) ?>" >
        </label>
      </p>
    </fieldset>
    <?php $i++ ?>
  <?php endforeach; ?>

  <input type="submit" value="Save changes">
  <a href="index.php">Cancel</a>
</form>
</body></html>
