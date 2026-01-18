<div class="blockLicense">
    <if:[param44]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                <span class="text-license">[param45]</span>
            </label>
        </div>
    </if>
    <if:[param46]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                <span class="text-license">[param47]</span>
            </label>
        </div>
    </if>
</div>
