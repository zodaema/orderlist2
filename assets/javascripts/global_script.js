$(document).ready(function() {
    // Load product types and other data
    $.ajax({
        url: 'get_data.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Populate product type dropdown
            const productTypeSelect = $('#productType');
            data.productTypes.forEach(function(type) {
                const option = $('<option>').val(type.id_product_type).text(type.name_th);
                productTypeSelect.append(option);
            });
        }
    });

    // Handle product type change
    $('#productType').change(function() {
        const productType = $(this).val();
        $('.productFields').hide();
        if (productType === '1') {
            $('#fobcaseFields').show();
        } else if (productType === '2') {
            $('#shortstrapFields').show();
        } else if (productType === '3') {
            $('#tagFields').show();
        } else if (productType === '4') {
            $('#metalpartFields').show();
        }
    });

    // Handle brand change
    $('#brand').change(function() {
        const brandId = $(this).val();
        $('#remoteCode').hide(); // Initially hide the remote code field
        $('#remoteCode').val(''); // Clear remote code field

        if (brandId) {
            $.ajax({
                url: 'get_data.php',
                method: 'GET',
                dataType: 'json',
                data: { action: 'getRemoteCodes', brandId: brandId },
                success: function(data) {
                    const remoteCodeSelect = $('#remoteCode');
                    remoteCodeSelect.empty(); // Clear existing options

                    if (data.remoteCodes.length > 0) {
                        remoteCodeSelect.show(); // Show the remote code field
                        data.remoteCodes.forEach(function(code) {
                            const option = $('<option>').val(code.sku).text(code.sku);
                            remoteCodeSelect.append(option);
                        });
                    } else {
                        remoteCodeSelect.hide(); // Hide if no options
                    }
                }
            });
        } else {
            $('#remoteCode').hide();
        }
    });

    // Add new product row
    $('#addProduct').click(function() {
        $('#productContainer').append($('#orderForm').clone().find('.productFields').show().end());
    });

    // Generate summary
    $('#generateSummary').click(function() {
        let summary = '';

        $('#productContainer .productFields').each(function() {
            const productType = $(this).closest('form').find('#productType').val();
            if (productType === '1') {
                const brand = $(this).find('#brand').val();
                const remoteCode = $(this).find('#remoteCode').val();
                const leatherType = $(this).find('#leatherType').val();
                const color = $(this).find('#color').val();
                summary += `📍${brand} ${remoteCode} ${leatherType} ${color} ${$(this).find('#price').val()} บาท\n`;
            } else if (productType === '2') {
                const leatherType = $(this).find('#leatherTypeShortstrap').val();
                const color = $(this).find('#colorShortstrap').val();
                const engravingText = $(this).find('#engravingText').val();
                const engravingColor = $(this).find('#engravingColor').val();
                const brassRingColor = $(this).find('#brassRingColor').val();
                summary += `📍สายคล้อง(${leatherType} สี${color}) สลักชื่อ ${engravingText} (สี${engravingColor}) + ห่วงกลมทองเหลืองแท้ สี${brassRingColor} ${$(this).find('#price').val()} บาท\n`;
            } else if (productType === '3') {
                const leatherType = $(this).find('#leatherTypeTag').val();
                const color = $(this).find('#colorTag').val();
                const engravingText = $(this).find('#engravingTextTag').val();
                const engravingColor = $(this).find('#engravingColorTag').val();
                summary += `📍ป้ายแท็ก(${leatherType} สี${color}) สลักชื่อ ${engravingText} (สี${engravingColor}) ${$(this).find('#price').val()} บาท\n`;
            } else if (productType === '4') {
                const metalpartCode = $(this).find('#metalpartCode').val();
                const color = $(this).find('#colorMetalpart').val();
                summary += `📍อะไหล่ ${metalpartCode} สี${color} ${$(this).find('#price').val()} บาท\n`;
            }
        });

        $('#orderSummary').val(summary);
    });

    // Copy to clipboard
    $('#copySummary').click(function() {
        const summaryText = $('#orderSummary').val();
        navigator.clipboard.writeText(summaryText).then(function() {
            alert('Summary copied to clipboard!');
        }, function(err) {
            console.error('Error copying text: ', err);
        });
    });

});
