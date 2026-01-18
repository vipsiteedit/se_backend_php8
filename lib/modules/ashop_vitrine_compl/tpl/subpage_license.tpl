<div class="blockLicense">
    <?php if(strval($section->parametrs->param346)=='Y'): ?>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" <?php echo $personal_checked ?> required>
                <?php echo $section->parametrs->param347 ?>
            </label>
        </div>
    <?php endif; ?>
    <?php if(strval($section->parametrs->param348)=='Y'): ?>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" <?php echo $additional_checked ?> required>
                <?php echo $section->parametrs->param349 ?>
            </label>
        </div>
    <?php endif; ?>
</div>
