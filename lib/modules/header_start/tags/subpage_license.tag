<div class="blockLicense">
    <if:[param24]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                [param25]
            </label>
        </div>
    </if>
    <if:[param26]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                [param27]
            </label>
        </div>
    </if>
</div>
