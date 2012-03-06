if (typeof Object.create !== 'function') {
    Object.create = function (o) {
        function F() {}
        F.prototype = o;
        return new F();
    };
}

/*
neuf.util.extend = function(o1, o2) {
    for(var key in o2) {
        if(o2.hasOwnProperty(key)) {
            o1[key] = o2[key];
        }
    }

    return o1;
}*/