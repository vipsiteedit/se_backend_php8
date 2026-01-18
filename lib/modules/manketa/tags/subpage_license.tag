<div class="blockLicense">
    <if:[param55]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                <span class="text-license">[param56]</span>
            </label>
        </div>
    </if>
    <if:[param57]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                <span class="text-license">[param58]</span>
            </label>
        </div>
    </if>
</div>
