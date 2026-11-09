function updateCodePreview() {
    const venueSelect = document.getElementById('venue_id');
    const productSelect = document.getElementById('product_id');
    const suffixInput = document.getElementById('suffix');
    const preview = document.getElementById('code_preview');

    if (venueSelect && productSelect && suffixInput && preview) {
        const vcode = venueSelect.options[venueSelect.selectedIndex].dataset.code || '';
        const pcode = productSelect.options[productSelect.selectedIndex].dataset.code || '';
        const suffix = suffixInput.value.trim() || '001';
        preview.textContent = `${vcode}-${pcode}-${suffix}`;
    }
}