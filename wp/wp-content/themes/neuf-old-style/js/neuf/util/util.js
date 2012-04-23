if (typeof Object.create !== 'function') {
    Object.create = function (o) {
        function F() {}
        F.prototype = o;
        return new F();
    };
}

neuf = {};

neuf.util = {
    capitalize: function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
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