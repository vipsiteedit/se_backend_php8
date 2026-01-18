<if:{$features_exist}>
<h3 class="titleHead" id="features">
    <span>[lang016]</span>
</h3>
<div class="content">
<div class="goodsFeatures">
    <repeat:feature_groups name=fgroup>
        <div class="featureGroup" data-id="[fgroup.id]">
            <noempty:[fgroup.id]>
            <div class="blockGroupName">
                <noempty:[fgroup.image]>
                    <img class="groupImage" src="[fgroup.image]">
                </noempty>
                <span class="groupName">[fgroup.name]</span>
                <noempty:[fgroup.description]>
                    <span class="markDescription">?</span>
                    <div class="contentDescription groupDescription" style="display:none;">
                        [fgroup.description]
                    </div>
                </noempty>
            </div>
            </noempty>
            <ul class="featureList">        
                <repeat:features[fgroup.id] name=feature>
                    <li class="featureItem" data-id="[feature.id]">
                        <noempty:[feature.image]>
                            <img class="featureImage" src="[feature.image]">
                        </noempty>
                        <label class="blockFeatureName">
                            <span class="featureName">[feature.name]<noempty:[feature.measure]>, <span class="measure">[feature.measure]</span></noempty></span>
                            <noempty:[feature.description]>
                                <span class="markDescription">?</span>
                                <div class="contentDescription featureDescription" style="display:none;">
                                    [feature.description]
                                </div>
                            </noempty>
                        </label>
                        <span class="blockFeatureValue">
                            <noempty:[feature.icon_image]>
                                <img class="valueIcon iconImage" src="[feature.icon_image]""></img>
                            </noempty>
                            <noempty:[feature.icon_color]>
                                <span class="valueIcon iconColor" style="background:[feature.icon_color];"></span>
                            </noempty>
                            <span class="featureValue">[feature.value]</span>
                        </span>
                    </li>
                </repeat:features[fgroup.id]>
            </ul> 
        </div>              
    </repeat:feature_groups>
</div>
</div>
</if>
