<div class="blockLicense">
    <if:[param49]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="personal_accepted" {$personal_checked} required>
                <span class="text-license">[param50]</span>
            </label>
        </div>
    </if>
    <if:[param51]=='Y'>
        <div class="license-item">
            <label>
                <input type="checkbox" name="additional_accepted" {$additional_checked} required>
                <span class="text-license">[param52]</span>
            </label>
        </div>
    </if>
</div>
