$$(".favorites li").each(function (li) {
    if (li.down(".description").getDimensions().height > 150) {
        li.addClassName("preview");
        li.down("a.more").observe("click", function (event) {
            event.stop();
            li.removeClassName("preview");
        });
    }
});
