<div class="blockLicense">
    <if:[param32]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                <span class="text-license">[param33]</span>
            </label>
        </div>
    </if>
    <if:[param34]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                <span class="text-license">[param35]</span>
            </label>
        </div>
    </if>
</div>
