<div class="blockLicense">
    <if:[param341]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                [param342]
            </label>
        </div>
    </if>
    <if:[param343]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                [param344]
            </label>
        </div>
    </if>
</div>
