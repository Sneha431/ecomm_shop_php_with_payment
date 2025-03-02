function requestNewArrivals() {
    fetchCall("newArrivals.php", responseNewArrivals);

    function responseNewArrivals(data) {
        const newArrivals = data.newArrivals;
        console.log(newArrivals);
        const newArrivalsSection = document.querySelector(".new-arrivals");
        populateCatalogue(newArrivals, newArrivalsSection);
    }
}