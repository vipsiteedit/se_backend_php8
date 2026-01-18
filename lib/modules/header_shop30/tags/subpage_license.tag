<div class="blockLicense">
    <if:[param24]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                <span class="text-license">[param25]</span>
            </label>
        </div>
    </if>
    <if:[param26]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                <span class="text-license">[param27]</span>
            </label>
        </div>
    </if>
</div>
