<se>
    <repeat:records>
        [subpage name=virtualshow]
    </repeat:records>
</se>
    <if:{$razdel}=={$_razdel}>
    <repeat:records>
        <if:[record.id]=={$obj}>
         [subpage name=virtualshow]
        </if>
    </repeat:records>
    <else>
    <serv>
        <if:{$obj}==0>
        [showrecord name=-1]
        <else>
        [showrecord name={$obj}]
        </if>
    </serv>
    </if>
