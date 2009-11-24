$$(".favorites .description").each(function (d) {
    if (d.getDimensions().height > 200) {
        d.addClassName("preview").down("a").observe("click", function (event) {
            event.stop();
            d.removeClassName("preview");
        });
    }
});
