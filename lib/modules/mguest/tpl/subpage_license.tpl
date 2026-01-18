<div class="blockLicense">
    <?php if(strval($section->parametrs->param44)=='Y'): ?>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" <?php echo $personal_checked ?> required>
                <span class="text-license"><?php echo $section->parametrs->param45 ?></span>
            </label>
        </div>
    <?php endif; ?>
    <?php if(strval($section->parametrs->param46)=='Y'): ?>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" <?php echo $additional_checked ?> required>
                <span class="text-license"><?php echo $section->parametrs->param47 ?></span>
            </label>
        </div>
    <?php endif; ?>
</div>
