// jshint ignore: start
+ function($) {

    // 获取四级分类菜单
    // 异步该为同步，确保数据的即时加载
    // $.ajax({
    //     url: api + '/teachertype/index',
    //     type: 'POST',
    //     dataType: 'json',
    //     async: false,
    //     success: function(response){
    //         console.log(response);
    //     }
    // })


    $.rawCitiesData = [{
            "code":2,
            "name":"父母课堂",
            "sub":[{
                    "code":9,
                    "name":"英文",
                },
                {
                    "code":10,
                    "name":"故事"
                },
                {
                    "code":11,
                    "name":"动画"
                },
                {
                    "code":12,
                    "name":"早教"
                },
                {
                    "code":13,
                    "name":"识字"
                }
            ]
        },
        {
            "code":3,
            "name":"宝宝试听",
            "sub":[
                {
                    "code":14,
                    "name":"英文"
                },
                {
                    "code":15,
                    "name":"故事"
                },
                {
                    "code":16,
                    "name":"动画"
                },
                {
                    "code":17,
                    "name":"早教"
                },
                {
                    "code":18,
                    "name":"识字"
                }
            ]
        },
        {
            "code":4,
            "name":"精品课堂",
            "sub":[
                {
                    "code":19,
                    "name":"精品"
                },
                {
                    "code":20,
                    "name":"幼儿园"
                },
                {
                    "code":21,
                    "name":"小学",
                    "sub":[
                        {
                            "code":24,
                            "name":"一年级"
                        },
                        {
                            "code":25,
                            "name":"二年级",
                            "sub":[
                                {
                                    "code":30,
                                    "name":"数学"
                                },
                                {
                                    "code":31,
                                    "name":"语文"
                                },
                                {
                                    "code":32,
                                    "name":"英语"
                                }
                            ]
                        },
                        {
                            "code":26,
                            "name":"三年级"
                        },
                        {
                            "code":27,
                            "name":"四年级"
                        },
                        {
                            "code":28,
                            "name":"五年级"
                        },
                        {
                            "code":29,
                            "name":"六年级"
                        }
                    ]
                },
                {
                    "code":22,
                    "name":"初中"
                },
                {
                    "code":23,
                    "name":"高中"
                }
            ]
        }
    ];

}($);
// jshint ignore: end

/* global $:true */
/* jshint unused:false*/

+

function($) {
    "use strict";

    var defaults;
    var raw = $.rawCitiesData;

    var format = function(data) {
        var result = [];
        for (var i = 0; i < data.length; i++) {
            var d = data[i];
            if (/^请选择|市辖区/.test(d.name)) continue;
            result.push(d);
        }
        if (result.length) return result;
        return [];
    };

    var sub = function(data) {
        if (!data.sub) return [{ name: '', code: data.code }]; // 有可能某些县级市没有区
        return format(data.sub);
    };

    var getCities = function(d) {
        for (var i = 0; i < raw.length; i++) {
            if (raw[i].code === d || raw[i].name === d) return sub(raw[i]);
        }
        return [];
    };

    var getDistricts = function(p, c) {
        for (var i = 0; i < raw.length; i++) {
            if (raw[i].code === p || raw[i].name === p) {
                for (var j = 0; j < raw[i].sub.length; j++) {
                    if (raw[i].sub[j].code === c || raw[i].sub[j].name === c) {
                        return sub(raw[i].sub[j]);
                    }
                }
            }
        }
    };

    var parseInitValue = function(val) {
        var p = raw[0],
            c, d, e;
        var tokens = val.split(' ');
        raw.map(function(t) {
            if (t.name === tokens[0]) p = t;
        });

        p.sub.map(function(t) {
            if (t.name === tokens[1]) c = t;
        })

        if (tokens[2]) {
            c.sub.map(function(t) {
                if (t.name === tokens[2]) d = t;
            })
        }

        // 添加四级分类
        // if (tokens[3]) {
        //     d.sub.map(function(t) {
        //         if (t.name === tokens[3]) e = t;
        //     })
        // }

        if (d) return [p.code, c.code, d.code];
        return [p.code, c.code];
    }

    $.fn.cityPicker = function(params) {
        params = $.extend({}, defaults, params);
        return this.each(function() {
            var self = this;

            var provincesName = raw.map(function(d) {
                return d.name;
            });
            var provincesCode = raw.map(function(d) {
                return d.code;
            });
            var initCities = sub(raw[0]);
            var initCitiesName = initCities.map(function(c) {
                return c.name;
            });
            var initCitiesCode = initCities.map(function(c) {
                return c.code;
            });
            var initDistricts = sub(raw[0].sub[0]);

            var initDistrictsName = initDistricts.map(function(c) {
                return c.name;
            });
            var initDistrictsCode = initDistricts.map(function(c) {
                return c.code;
            });

            // 第四级的name及code
            // var initDistrictsClass = sub(raw[0].sub[0].sub[0]);
            //
            // var initDistrictsNameClass = initDistricts.map(function(c) {
            //     return c.name;
            // });
            // var initDistrictsCodeClass = initDistricts.map(function(c) {
            //     return c.code;
            // });

            var currentProvince = provincesName[0];
            var currentCity = initCitiesName[0];
            var currentDistrict = initDistrictsName[0];
            // var currentDistrict2 = initDistrictsNameClass[0];

            var cols = [{
                    displayValues: provincesName,
                    values: provincesCode,
                    cssClass: "col-province"
                },
                {
                    displayValues: initCitiesName,
                    values: initCitiesCode,
                    cssClass: "col-city"
                }
            ];

            if (params.showDistrict) cols.push({
                values: initDistrictsCode,
                displayValues: initDistrictsName,
                cssClass: "col-district"
            });

            var config = {

                cssClass: "city-picker",
                rotateEffect: false, //为了性能
                formatValue: function(p, values, displayValues) {
                    return displayValues.join(' ');
                },
                onChange: function(picker, values, displayValues) {
                    var newProvince = picker.cols[0].displayValue;
                    var newCity;
                    if (newProvince !== currentProvince) {
                        var newCities = getCities(newProvince);
                        newCity = newCities[0].name;
                        var newDistricts = getDistricts(newProvince, newCity);
                        picker.cols[1].replaceValues(newCities.map(function(c) {
                            return c.code;
                        }), newCities.map(function(c) {
                            return c.name;
                        }));
                        if (params.showDistrict) picker.cols[2].replaceValues(newDistricts.map(function(d) {
                            return d.code;
                        }), newDistricts.map(function(d) {
                            return d.name;
                        }));
                        currentProvince = newProvince;
                        currentCity = newCity;
                        picker.updateValue();
                        return false; // 因为数据未更新完，所以这里不进行后序的值的处理
                    } else {
                        if (params.showDistrict) {
                            newCity = picker.cols[1].displayValue;
                            if (newCity !== currentCity) {
                                var districts = getDistricts(newProvince, newCity);
                                picker.cols[2].replaceValues(districts.map(function(d) {
                                    return d.code;
                                }), districts.map(function(d) {
                                    return d.name;
                                }));
                                currentCity = newCity;
                                picker.updateValue();
                                return false; // 因为数据未更新完，所以这里不进行后序的值的处理
                            }
                        }
                    }
                    //如果最后一列是空的，那么取倒数第二列
                    var len = (values[values.length - 1] ? values.length - 1 : values.length - 2)
                    $(self).attr('data-code', values[len]);
                    $(self).attr('data-codes', values.join(','));
                    if (params.onChange) {
                        params.onChange.call(self, picker, values, displayValues);
                    }
                },

                cols: cols
            };

            if (!this) return;
            var p = $.extend({}, params, config);
            //计算value
            var val = $(this).val();
            if (!val) val = '父母课堂 英文';
            currentProvince = val.split(" ")[0];
            currentCity = val.split(" ")[1];
            currentDistrict = val.split(" ")[2];
            // currentDistrict2 = val.split(" ")[3];
            if (val) {
                p.value = parseInitValue(val);
                if (p.value[0]) {
                    var cities = getCities(p.value[0]);
                    p.cols[1].values = cities.map(function(c) {
                        return c.code;
                    });
                    p.cols[1].displayValues = cities.map(function(c) {
                        return c.name;
                    });
                }

                if (p.value[1]) {
                    if (params.showDistrict) {
                        var dis = getDistricts(p.value[0], p.value[1]);
                        p.cols[2].values = dis.map(function(d) {
                            return d.code;
                        });
                        p.cols[2].displayValues = dis.map(function(d) {
                            return d.name;
                        });
                    }
                } else {
                    if (params.showDistrict) {
                        var dis = getDistricts(p.value[0], p.cols[1].values[0]);
                        p.cols[2].values = dis.map(function(d) {
                            return d.code;
                        });
                        p.cols[2].displayValues = dis.map(function(d) {
                            return d.name;
                        });
                    }
                }
            }
            $(this).picker(p);
        });
    };

    defaults = $.fn.cityPicker.prototype.defaults = {
        showDistrict: true //是否显示地区选择
    };

}($);