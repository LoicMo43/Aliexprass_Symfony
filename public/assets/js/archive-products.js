

// init Isotope

var priceMin = 0;

var priceMax = 100;



var $grid = jQuery('.resultat-category').isotope({
    itemSelector: '.element-item',
    layoutMode: 'fitRows',
    filter: function () {

        var isMatched = true;
        var $this = jQuery(this);

        for (var prop in filters) {
            var filter = filters[prop];
            // use function if it matches
            filter = filterFns[filter] || filter;
            // test each filter
            if (filter) {
                isMatched = isMatched && jQuery(this).is(filter);
            }
            // break if not matched
            if (!isMatched) {
                break;
            }
        }
        return isMatched;
    },
    getSortData: {
        croissant: '.productPrice parseInt',
        decroissant: '.productPrice parseInt',
        meilleur: '[data-category]',

    },
    sortAscending: {
        croissant: true,
        decroissant: false,
        meilleur: true,
    }
});

// store filter for each group
var filters = {};

var filterFns = {
    // show if number is greater than 50
    numberSup: function () {
        // use $(this) to get item element
        var number = parseFloat(jQuery(this).find('.indice').data("indice"));
        return parseInt(number, 10) >= priceMin;
    },
    numberInf: function () {
        // use $(this) to get item element
        var number = parseFloat(jQuery(this).find('.indice').data("indice"));
        return parseInt(number, 10) <= priceMax;
    }
};

jQuery("#selectSorting").on({
    change: function () {
        var sortValue = jQuery(this).find(':selected').val();
        console.log(sortValue);
        $grid.isotope({
            sortBy: sortValue
        });
    }
});

jQuery('.filters').on('click', '.button', function () {
    var $this = jQuery(this);

    // get group key
    var $buttonGroup = $this.parents('.button-group');
    var filterGroup = $buttonGroup.attr('data-filter-group');
    // set filter for group
    priceMin = jQuery('.priceInfB').attr('data-value');
    priceMax = jQuery('.priceSupB').attr('data-value');
    console.log("Iso : " + priceMin + " " + priceMax)
    filters[filterGroup] = $this.attr('data-filter');
    console.log(filters);
    // arrange, and use filter fn
    $grid.isotope();
});

// change is-checked class on buttons
jQuery('.button-group').each(function (i, buttonGroup) {
    var $buttonGroup = jQuery(buttonGroup);
    $buttonGroup.on('click', 'button', function (event) {
        $buttonGroup.find('.is-checked').removeClass('is-checked');
        var $button = jQuery(event.currentTarget);
        $button.addClass('is-checked');
    });
});


var $anyButtons = jQuery('.filters').find('button[data-filter=""]');
var $buttons = jQuery('.filters button');

jQuery('.button--reset').on('click', function () {
    // reset filters
    Object.keys(filters).forEach(key => {
        filters[key] = "";
    });
    $grid.isotope();
    // reset buttons
    $buttons.removeClass('is-checked');
    $anyButtons.addClass('is-checked');

    var maxValue = jQuery('input[name="rangeOne"]').attr('max');


    jQuery('.outputOne').html(0 + ' %');
    jQuery('.outputOne').css('left', 0 + '%');
    jQuery('.outputTwo').html(maxValue + ' %');
    jQuery('.outputTwo').css('left', 100 + '%');
    jQuery('.incl-range').css('width', 100 + '%');
    jQuery('.incl-range').css('left', 0 + '%');
    jQuery('input[name="rangeTwo"]').val(maxValue);
    jQuery('input[name="rangeOne"]').val(0);
    jQuery('.priceSupB').attr('data-value', maxValue);
    jQuery('.priceInfB').attr('data-value', 0);
    jQuery('.priceSup').find('button').trigger('click');
    jQuery('.priceInf').find('button').trigger('click');

});

// flatten object by concatting values
function concatValues(obj) {
    var value = '';
    for (var prop in obj) {
        value += obj[prop];
    }
    return value;
}


var rangeOne = jQuery('input[name="rangeOne"]'),
    rangeTwo = jQuery('input[name="rangeTwo"]'),
    outputOne = document.querySelector('.outputOne'),
    outputTwo = document.querySelector('.outputTwo'),
    updateView = function () {
        $this = $(this);
        if ($this.attr('name') === 'rangeOne') {
            $('.outputOne').html($this.val() + ' %');
            $('.outputOne').css('left', $this.val() / $this.attr('max') * 100 + '%');
            jQuery('.priceInfB').removeClass('is-checked');
            jQuery('.priceInfB').attr('data-value', $this.val());
            jQuery('.priceInf').find('button').trigger('click');
        } else {
            $('.outputTwo').css('left', $this.val() / $this.attr('max') * 100 + '%');
            $('.outputTwo').html($this.val() + ' %');
            jQuery('.priceSupB').removeClass('is-checked');
            jQuery('.priceSupB').attr('data-value', $this.val());
            jQuery('.priceSup').find('button').trigger('click');
        }
        if (parseInt($('input[name="rangeOne"]').val()) > parseInt($('input[name="rangeTwo"]').val())) {
            $('.incl-range').css('width', ($('input[name="rangeOne"]').val() - $('input[name="rangeTwo"]').val()) / $this.attr('max') * 100 + '%');
            $('.incl-range').css('left', $('input[name="rangeTwo"]').val() / $this.attr('max') * 100 + '%');
        } else {
            $('.incl-range').css('width', ($('input[name="rangeTwo"]').val() - $('input[name="rangeOne"]').val()) / $this.attr('max') * 100 + '%');
            $('.incl-range').css('left', $('input[name="rangeOne"]').val() / $this.attr('max') * 100 + '%');
        }
    };


jQuery(document).ready(function ($) {

    $('.p-r-d').append('<section class="range-slider slider-container"><span class="output outputOne"></span><span class="output outputTwo"></span><span class="full-range"></span><span class="incl-range" id="incl-range-d"></span><input name="rangeOne" value="0" min="0" max="100" step="1" type="range"><input name="rangeTwo" value="100" min="0" max="100" step="1" type="range"></section>');

    updateView.call(rangeOne);
    updateView.call(rangeTwo);
    $('input[type="range"]').on('mouseup', function () {
        this.blur();
    }).on('mousedown input', function () {
        updateView.call(this);
    });

    $('.outputOne').html(0 + ' %');
    $('.outputTwo').html(100 + ' %');
    $('.outputOne').css('left', 0 + '%');
    jQuery('.priceInfB').removeClass('is-checked');
    jQuery('.priceInfB').attr('data-value', 0);
    jQuery('.priceInf').find('button').trigger('click');
});
