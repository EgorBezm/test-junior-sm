document.addEventListener("DOMContentLoaded", topIcons);

function topIcons()
{
    let $top = document.getElementsByClassName('table__top');

    for (let $el of $top) {
        $el.classList.remove('bg_gold', 'bg_silver', 'bg_bronze');
    }

    if ($top[0].innerHTML === "1")
        $top[0].classList.add('bg_gold');
    if ($top[1].innerHTML === "2")
        $top[1].classList.add('bg_silver');
    if ($top[2].innerHTML === "3")
        $top[2].classList.add('bg_bronze');
}

let thisPage = document.getElementById('page').getAttribute('data-page');

let params = window
    .location
    .search
    .replace('?','')
    .split('&')
    .reduce(
        function(p,e){
            let a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
    );

document.getElementById('page-next').onclick = function() {
    openPage(params['sort'], params['sort-method'],Number(thisPage) + 1)
}

document.getElementById('page-back').onclick = function() {
    openPage(params['sort'], params['sort-method'],Number(thisPage) - 1)
}

function openPage(sort = "sum", sort_method = "b-s", page = 1)
{
    window.location.href = window.location.origin +
        '?sort=' + sort +
        '&sort-method=' + sort_method +
        '&page=' + page;
}

function toSort(el)
{
    let method = "b-s";
    if (el.classList.contains("sort_b-s"))
    {
        method = "s-b";
    }

    openPage(el.getAttribute('data-id'), method, params['page']);
}