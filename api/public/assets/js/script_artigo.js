$(function() {
    if (document.getElementById('is_stock').checked) {
        document.getElementById('enable_is_stock_1').style.display = 'block';
        document.getElementById('enable_is_stock_2').style.display = 'block';
    } else {
        document.getElementById('enable_is_stock_1').style.display = 'none';
        document.getElementById('enable_is_stock_2').style.display = 'none';
    }
});

function enable_is_stock() {

    if (document.getElementById('is_stock').checked) {
        document.getElementById('enable_is_stock_1').style.display = 'block';
        document.getElementById('enable_is_stock_2').style.display = 'block';
    } else {
        document.getElementById('enable_is_stock_1').style.display = 'none';
        document.getElementById('enable_is_stock_2').style.display = 'none';
    }
}