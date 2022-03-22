<div class="bx_block_filter_inputs">
    <h3>Бренд</h3>
    <input type="text" id="sidebar-search" placeholder="Поиск бренда">
    <?foreach($arItem['VALUES'] as $brandID => $value):?>
        <div class="bx-filter-parameters-box">
            <span class="bx-filter-container-modef"></span>
            <label class="checkbox-ui">
                <input type="checkbox"
                       name="<? echo $value["CONTROL_NAME"] ?>"
                       value="<? echo $value["HTML_VALUE"] ?>"
                       id="<? echo $value["CONTROL_ID"] ?>"
                       <? echo $value["CHECKED"]? 'checked="checked"': '' ?>
                       onclick="smartFilter.click(this)"
                >
                <div class="checkbox-item"></div>
                <div><?=$value['VALUE']?></div>
            </label>
        </div>
    <?endforeach;?>
    <hr>
</div>
