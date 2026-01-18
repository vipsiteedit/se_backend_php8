<div class="blockLicense">
    <?php if(strval($section->parametrs->param21)=='Y'): ?>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" <?php echo $personal_checked ?> required>
                <span class="text-license"><?php echo $section->parametrs->param22 ?></span>
            </label>
        </div>
    <?php endif; ?>
    <?php if(strval($section->parametrs->param24)=='Y'): ?>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" <?php echo $additional_checked ?> required>
                <span class="text-license"><?php echo $section->parametrs->param25 ?></span>
            </label>
        </div>
    <?php endif; ?>
</div>
