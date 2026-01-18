<div class="blockLicense">
    <if:[param346]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                [param347]
            </label>
        </div>
    </if>
    <if:[param348]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                [param349]
            </label>
        </div>
    </if>
</div>
