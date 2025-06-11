$(document).ready(function() {

    // ***** Template zone *****
    var productCounter = 1;
    var templates = {
        leatherRow: function (counter) {
            return `
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <div class="form-group leatherTypeGroup">
                            <label for="leatherType">Leather Type: </label><br>
                            <input class="btn-check leatherType" type="radio" name="leatherType${counter}" id="chevreLeather${counter}" value="1" required>
                            <label class="btn btn-outline-primary" for="chevreLeather${counter}">หนังแพะ</label>
                            <input class="btn-check leatherType" type="radio" name="leatherType${counter}" id="calfskinLeather${counter}" value="2">
                            <label class="btn btn-outline-primary" for="calfskinLeather${counter}">หนังวัว</label>
                            <input class="btn-check leatherType" type="radio" name="leatherType${counter}" id="canvasLeather${counter}" value="5">
                            <label class="btn btn-outline-primary" for="canvasLeather${counter}">หนังแคนวาส</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mixColorSelect">
                        <input class="btn-check mixColor" type="checkbox" id="mixColor${counter}">
                        <label class="btn btn-outline-primary" for="mixColor${counter}">
                            Mix Color
                        </label>

                    </div>
                    <div class="form-group leatherColorGroup">
                        <label for="leatherColor">Color</label>
                        <select id="leatherColor" class="form-select leatherColor">
                            <option value="">Select Color</option>
                        </select>
                    </div>

                    <div class="form-group leatherMixColorGroup" style="display:none;">
                        <label for="frontColor">Front Color</label>
                        <select name="frontColor" id="frontColor" class="form-select leatherColor">
                            <option value="">Select Color</option>
                        </select>
                        <label for="backColor">Back Color</label>
                        <select name="backColor" id="backColor" class="form-select leatherColor">
                            <option value="">Select Color</option>
                        </select>
                        <label for="lockStripColor">Lock Strip Color</label>
                        <select name="lockStripColor" id="lockStripColor" class="form-select leatherColor">
                            <option value="">Select Color</option>
                        </select>
                    </div>
                </div>
            </div>`},

            punchHole: function (counter) {
            return `
            <div class="row mb-3 align-items-center">
                <div class="col-md-12">
                    <div class="form-group punchHoleGroup">
                            <label for="punchHole">Custom Punch: </label>
                            <input class="btn-check punchButton" type="checkbox" id="punchButton${counter}">
                            <label class="btn btn-outline-primary btn-lg" for="punchButton${counter}">เจาะปุ่ม</label>
                            <input class="btn-check punchLogo" type="checkbox" id="punchLogo${counter}">
                            <label class="btn btn-outline-primary btn-lg" for="punchLogo${counter}">เจาะโลโก้</label>
                    </div>
                </div>
            </div>
            `},

        engravingRow: `
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <div class="form-group engravingGroup">
                        <label for="engravingName">Engraving Name</label>
                        <input type="text" class="form-control engravingName">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group engravingColorGroup">
                        <!-- Engraving color options will be loaded here -->
                    </div>
                </div>
            </div>`,
        metalTypeRow: function (counter) {
            return `
            <div class="row mb-3 align-items-end">
                <div class="col-md-6">
                    <div class="form-group metalTypeGroup mb-3">
                        <label for="metalType">Metal Type</label>
                        <select class="form-select metalType" required>
                            <option value="">Select Metal Type</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input name="addOnMetal" class="form-check-input addOnMetal" type="checkbox" id="addOnMetal${counter}">
                        <label class="form-check-label" for="addOnMetal${counter}">เป็นแบบสั่งแยก</label>
                    </div>
                </div>
            </div>`},
        metalColorRow: `<div class="form-group metalColorGroup mb-3"></div>`,
    };

    // ***** ENDING: Template zone *****

    // ***** AJAX zone *****
    // Load Product Types
    $.ajax({
        url: 'get_data.php',
        method: 'GET',
        data: { action: 'getProductTypes' },
        dataType: 'json',
        success: function(data) {
            var productTypeSelect = $('.productType');
            $.each(data, function(index, item) {
                productTypeSelect.append(`<option value="${item.id_product_type}">${item.name_th}</option>`);
            });
        }
    });

    function clearEntryState (Entry){
        // // Show/hide fields based on Product Type
        Entry.find('.brandGroup, .remoteCodeGroup').hide();
        Entry.find('.metalColorGroup').remove();
        Entry.find('.metalTypeGroup, .engravingGroup, .leatherTypeGroup, .leatherColorGroup, .leatherMixColorGroup, .punchHoleGroup').closest('div.row').remove();
    }

    function getBrands(productTypeId, Entry){
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: { action: 'getBrands', productTypeId: productTypeId },
            dataType: 'json',
            success: function(data) {
                Entry.append($(templates.leatherRow(productCounter)));

                var brandSelect = Entry.find('.brand');
                brandSelect.empty().append('<option value="">Select Brand</option>');
                $.each(data, function(index, item) {
                    brandSelect.append(`<option value="${item.id_brand}">${item.name}</option>`);
                });
                Entry.find('.brandGroup').toggle(productTypeId == 1); // Only show for 'Key FOB Case'
                Entry.find('.remoteCodeGroup').toggle();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ', textStatus, errorThrown);
            }
        });
    }

    function getRemoteCodes (brandId, Entry){
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: { action: 'getRemoteCodes', brandId: brandId },
            dataType: 'json',
            success: function(data) {
                var remoteCodeSelect = Entry.find('.remoteCode');
                remoteCodeSelect.empty().append('<option value="">Select Remote Code</option>');
                $.each(data, function(index, item) {
                    remoteCodeSelect.append(`<option value="${item.sku}" data-slang="${item.slang_th}" data-button="${item.button}" data-price="${item.price}">${item.sku}</option>`);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ', textStatus, errorThrown);
            }
        });
    }

    function getLeatherColor(leatherTypeID, Entry){
        // Load Leather Types -> Colors
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: { action: 'getColors', leatherTypeID: leatherTypeID},
            dataType: 'json',
            success: function(data) {
                var colorSelect = Entry.find('.leatherColor');
                colorSelect.empty().append('<option value="">Select Color</option>');
                $.each(data, function(index, item) {
                    colorSelect.append(`<option value="${item.id_product_color}">${item.name_en}</option>`);
                });
            }
        });
    }

    function getMetalType (productTypeId, Entry){
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: { action: 'getMetalType', productTypeId: productTypeId },
            dataType: 'json',
            success: function(data) {
                var metalType = Entry.find('.metalType');
                metalType.empty().append('<option value="">Select Metal Type</option>');
                $.each(data, function(index, item) {
                    metalType.append(`<option value="${item.sku}" data-price="${item.price}">${item.slang_th}</option>`);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ', textStatus, errorThrown);
            }
        });
    }

    function getMetalColor (Entry){
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: { action: 'metalColor'},
            dataType: 'json',
            success: function(data) {
                var metalColor = Entry.find('.metalColorGroup');
                metalColor.empty().append('<label for="metalColorGroup">Metal Color: </label>');
                $.each(data, function(index, item) {
                    var html = `<input class="btn-check metalColor" type="radio" name="metalColor${productCounter}" id="${item.id_product_color}${productCounter}" value="${item.name_th}" required>
                        <label class="btn btn-outline-`;

                        switch(item.name_en) {
                            case 'Gold':
                                html += 'warning';
                            break;
                            case 'Silver':
                                html += 'secondary';
                            break;
                            case 'Black':
                                html += 'dark';
                            break;
                        }
                        
                        html +=`" for="${item.id_product_color}${productCounter}">
                            ${item.name_th}
                        </label>`

                    metalColor.append(html);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ', textStatus, errorThrown);
            }
        });
    }

    function getEngravingColor(Entry){
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: { action: 'getEngravingColor'},
            dataType: 'json',
            success: function(data) {
                var engravingColor = Entry.find('.engravingColorGroup');
                engravingColor.empty().append('<label for="engravingColorGroup">Engraving Color: </label>');
                $.each(data, function(index, item) {
                    var html = `<input class="btn-check engravingColor" type="radio" name="engravingColor${productCounter}" id="${item.id_product_color}${productCounter}" value="${item.name_th}" required>
                        <label class="btn btn-outline-`;

                        switch (item.name_en){
                            case 'Gold Foil':
                                html += 'warning';
                            break;
                            case 'Silver Foil':
                                html += 'secondary';
                            break;
                            case 'Embossed':
                                html += 'dark';
                            break;
                        }
                        
                        html +=`" for="${item.id_product_color}${productCounter}">
                            ${item.name_th}
                        </label>`

                    engravingColor.append(html);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ', textStatus, errorThrown);
            }
        });
    }

    // ***** ENDING: AJAX zone *****
    

    // ***** ON CHANGE zone *****
    // Event: Product Type Change
    $(document).on('change', '.productType', function() {
        var productTypeId = $(this).val();
        var $parentEntry = $(this).closest('.product-entry');
        
        clearEntryState($parentEntry);

        switch (productTypeId){
            case '1':
                $parentEntry.append($(templates.punchHole(productCounter)));
    
                getBrands(productTypeId, $parentEntry);
            break;

            case '2':
                $parentEntry.append($(templates.leatherRow(productCounter)));
                $parentEntry.append(templates.engravingRow);
                $parentEntry.append(templates.metalColorRow);
                $parentEntry.find('.mixColorSelect, .leatherMixColorGroup').hide();
    
                getMetalColor($parentEntry);
                getEngravingColor($parentEntry);
            break;

            case '3':
    
                $parentEntry.append($(templates.leatherRow(productCounter)));
                $parentEntry.append(templates.engravingRow);
                $parentEntry.find('.mixColorSelect, .leatherMixColorGroup').hide();
    
                getEngravingColor($parentEntry);
            break;

            case '4':
                $parentEntry.append($(templates.metalTypeRow(productCounter)));
                $parentEntry.append(templates.metalColorRow);
    
                getMetalType(productTypeId, $parentEntry);
                getMetalColor($parentEntry);
            break;
        }
        
    });

    // Event: Brand Change
    $(document).on('change', '.brand', function() {
        var brandId = $(this).val();
        var $parentEntry = $(this).closest('.product-entry');

        getRemoteCodes(brandId, $parentEntry);
    });

    $(document).on('change', '.mixColor', function() {
        var $parentEntry = $(this).closest('.product-entry');

        $parentEntry.find('.leatherColorGroup, .leatherMixColorGroup').closest('.leatherColor').val([]);
        $parentEntry.find('.leatherColorGroup, .leatherMixColorGroup').toggle();
    });

    $(document).on('change', '.leatherType', function() {
        var leatherTypeID = $(this).val();
        var $parentEntry = $(this).closest('.product-entry');

        // Load Leather Types -> Colors
        getLeatherColor(leatherTypeID, $parentEntry);
    });

    $(document).on('click', '.addProduct', function() {
        productCounter++;
        var newEntry = $('.product-entry:first').clone();
        var removeButton = '<div class="d-grid gap-2" style="padding-bottom:1em;"><button class="btn btn-danger removeProduct">X</button></div>';
        newEntry.find('input, select').val('').prop('disabled', false);
        clearEntryState(newEntry);
        $('#productContainer').append(newEntry);
        newEntry.append(removeButton);
    });

    $(document).on('click', '.removeProduct', function() {
        productCounter = productCounter-1;
        var $parentEntry = $(this).closest('.product-entry');
        $parentEntry.remove();
    });

    // Event: Generate Summary
    $(document).on('click', '.generateSummary', function() {
        var totalPrice = 0;
        var summary = 'สรุปยอดให้นะครับ\n';

        $('.product-entry').each(function() {
            
            // CONFIG ZONE
            var shortStrapPrice = 320; 
            var tagPrice = 140;
            var remoteCase_topupPrice_chevreLeather = 800;
            var remoteCase_topupPrice_canvasLeather = 2800;
            var shortStrap_topupPrice_chevreLeather = 100;
            var shortStrap_topupPrice_canvasLeather = 300;
            var tag_topupPrice_chevreLeather = 50;
            var tag_topupPrice_canvasLeather = 250;
            var chevreLeather_TH = 'หนังแพะฝรั่งเศส';
            var canvasLeather_TH = 'หนัง the Chain of rebello';
            // END CONFIG ZONE

            var $entry = $(this);
            var productType = $entry.find('.productType option:selected').text();
            var brand = $entry.find('.brand option:selected').text();
            var remoteCode = $entry.find('.remoteCode option:selected').text();
            var remoteSlang = $entry.find('.remoteCode option:selected').attr('data-slang');
            var remotePrice = $entry.find('.remoteCode option:selected').attr('data-price');
            var remoteButton = $entry.find('.remoteCode option:selected').attr('data-button');
            var punchButton = $entry.find('.punchButton');
            var punchLogo = $entry.find('.punchLogo');
            var leatherTypeId = $entry.find('.leatherType:checked').attr('id');
            var leatherType = $.trim($entry.find("label[for='"+leatherTypeId+"']").text());
            var leatherTypeName = leatherType;
            var leatherColor = $entry.find('#leatherColor option:selected').text();
            var mixColorSelect = $entry.find('.mixColor');
            var frontLeatherColor = $entry.find('#frontColor option:selected');
            var backLeatherColor = $entry.find('#backColor option:selected');
            var lockStripLeatherColor = $entry.find('#lockStripColor option:selected');
            var engravingName = $entry.find('.engravingName').val();
            var engravingColor = $entry.find('.engravingColor:checked').val();
            var metalType = $entry.find('.metalType option:selected').text();
            var metalColor = $entry.find('.metalColor:checked').val();
            var addOnMetalCheckbox = $entry.find('.addOnMetal');
            var metalPrice = $entry.find('.metalType option:selected').attr('data-price');

            if (productType != ''){
                switch(productType) {
                    case 'สายคล้องสลักชื่อ' :
                        switch(leatherType) {
                            case 'หนังแพะ':
                                leatherTypeName = chevreLeather_TH;
                                shortStrapPrice += shortStrap_topupPrice_chevreLeather;
                            break;
                            case 'หนังแคนวาส':
                                leatherTypeName = canvasLeather_TH;
                                shortStrapPrice += shortStrap_topupPrice_canvasLeather;
                            break;
                            default:
                            // code to be executed if n is different from case 1 and 2
                        }
    
                        summary += `📍สายคล้อง(${leatherTypeName} สี${leatherColor}) สลักชื่อ ${engravingName} (${engravingColor}) + ห่วงกลมทองเหลืองแท้ ${metalColor} ${shortStrapPrice}บาท\n`;
                        totalPrice += parseInt(shortStrapPrice);
                    break;

                    case 'เคสรีโมทรถยนต์':
                        switch(leatherType) {
                            case 'หนังแพะ':
                                leatherTypeName = chevreLeather_TH;
                                remotePrice = parseInt(remotePrice)+remoteCase_topupPrice_chevreLeather;
                            break;
                            case 'หนังแคนวาส':
                                leatherTypeName = canvasLeather_TH;
                                remotePrice = parseInt(remotePrice)+remoteCase_topupPrice_canvasLeather;
    
                            break;
                            default:
                            // code to be executed if n is different from case 1 and 2
                        }
    
                        summary += `📍${remoteSlang} (${remoteCode}) ${remoteButton}ปุ่ม` 
                        if(punchButton.is(':checked')) summary += ` เจาะเปิดปุ่ม`;
                        if(punchLogo.is(':checked')) summary += ` เจาะโลโก้`;
    
                        summary += ` ${leatherTypeName} สี`;
    
                        if(mixColorSelect.is(':checked')) {
                            if(backLeatherColor.val() != ''){
                                summary += `หน้า${frontLeatherColor.text()} หลัง${backLeatherColor.text()} สายล็อค${lockStripLeatherColor.text()}`;
                            }
                            else {
                                summary += `${frontLeatherColor.text()} สายล็อค${lockStripLeatherColor.text()}`;
                            }
                        }
                        else summary += `${leatherColor}`;
                        summary += ` ${remotePrice}บาท\n`;
                        totalPrice += parseInt(remotePrice);
                    break;


                    case 'ป้ายแท็ก':
                        switch(leatherType) {
                            case 'หนังแพะ':
                                leatherTypeName = chevreLeather_TH;
                                tagPrice += tag_topupPrice_chevreLeather;
                            break;
                            case 'หนังแคนวาส':
                                leatherTypeName = canvasLeather_TH;
                                tagPrice += tag_topupPrice_canvasLeather;
                            break;
                            default:
                            // code to be executed if n is different from case 1 and 2
                        }

                        summary += `📍${productType}(${leatherTypeName} สี${leatherColor}) สลักชื่อ ${engravingName} (${engravingColor}) ${tagPrice}บาท\n`;
                        totalPrice += parseInt(tagPrice);
                    break;


                    case 'อะไหล่':
                        if(addOnMetalCheckbox.is(':not(:checked)')){
                            metalPrice = parseInt(metalPrice)-50;
                        }
                        summary += `📍${metalType} (${metalColor}) `;
                        if(metalPrice != 0){
                            summary += `${metalPrice}บาท\n`;
                        }
                        else  summary += `\n`;
                        totalPrice += parseInt(metalPrice);
                    break;
                }
            }
            
        });
        if(totalPrice >= 900){
            summary += `\nฟรีค่าจัดส่ง\n`;
        }
        else{
            summary += `\nค่าจัดส่ง 50บาท\n`;
            var shippingPrice = '50';
            totalPrice += parseInt(shippingPrice);
        }

        summary += `\nยอดรวม ${Number(totalPrice).toLocaleString('en')} บาทครับ😊🙏

**ระยะเวลาจัดทำประมาณ 1-3 อาทิตย์ เนื่องจากเป็นงานแฮนเมดทำทีละชิ้น และต้องรันตามลำดับคิวของลูกค้านะครับ

กรุณาอ่านเงื่อนไขก่อนทำการสั่งซื้อ
- เงื่อนไขการรับประกันสินค้า (https://rebello.shop/faqs/#1705671165524-bdc9782f-dc40)
- เงื่อนไขการการคืน หรือยกเลิกสินค้า (https://rebello.shop/faqs/#1705670872659-eb730777-962e)\n`;

        $('#summaryTextarea').val(summary.trim());
    });

    // Event: Copy to Clipboard
    $(document).on('click', '#copyToClipboard', function() {
        var copyText = $('#summaryTextarea').val();
        navigator.clipboard.writeText(copyText).then(function() {
            alert('Summary copied to clipboard');
        }, function(err) {
            console.error('Failed to copy text: ', err);
        });
    });

});
