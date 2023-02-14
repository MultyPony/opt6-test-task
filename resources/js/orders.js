import './bootstrap';
import Cookies from 'js-cookie';
import {round} from "lodash";
import moment from "moment";

document.addEventListener('alpine:init', () => {
    const orderForm = document.getElementById('order-form');
    const data = new FormData(orderForm);
    const products = data.get('products').length > 0 ? JSON.parse(data.get('products')) : [];

    window.Alpine.data('getData', () => {
        return {
            formData: {
                created_at: data.get('created_at'),
                telephone: data.get('telephone'),
                email: data.get('email'),
                address: data.get('address'),
                products: products
            },
            validation: {
                created_at() {
                    let date = moment(this.formData.created_at, 'DD.MM.YYYY');

                    if (this.formSubmitStarted) {
                        return !date.isValid() && this.formData.created_at.length < 10;
                    }
                    return !date.isValid() &&
                        this.formData.created_at.length > 0 &&
                        this.formData.created_at.length < 10;
                },
                telephone() {
                    let neededLength = this.regexForTelephone(this.formData.telephone).length;

                    if (this.formSubmitStarted) {
                        return this.formData.telephone.length < neededLength;
                    }
                    return this.formData.telephone.length > 0 && this.formData.telephone.length < neededLength;
                },
                email() {
                    let re = /\S+@\S+\.\S+/;
                    let result = re.test(this.formData.email);

                    if (!this.formSubmitStarted) {
                        return this.formData.email.length > 0 && !result;
                    }
                    return !result;
                },
                address() {
                    if (this.formSubmitStarted) {
                        // TODO учесть результат геокодера
                        return this.formData.address.length === 0 || this.addressError.length > 0;
                    }
                    return this.formData.address.length !== 0 && this.addressError.length > 0;
                },
                products() {
                    return this.formData.products.length  > 0;
                }
            },
            addressError: "",
            addressHint: "",
            formSubmitStarted: false,
            lastProductSearch: [],
            modalOpened: false,

            regexForTelephone(telephone) {
                if (telephone.startsWith('+')) {
                    return telephone.startsWith('+7 ') ? '+7 999 999 99 99' : '+79999999999';
                } else {
                    return '89999999999';
                }
            },
            calculateTotal() {
                let total = 0;
                this.formData.products.forEach(product => {
                    total += (product.price * product.count);
                });
                return round(total, 2);
            },
            searchProductByName(name) {
                fetch(`/api/products?name=${name}`, {
                    headers: {
                        'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN'),
                        'Accept': 'application/json',
                    },
                }).then((response) => response.json())
                    .then((products) => {
                        console.log('Success:', products);
                        this.lastProductSearch = products.data;
                    }).catch((error) => {
                    console.error('Error:', error);
                });
            },
            addProduct(product) {
                this.formData.modalOpened = false;
                const found = this.formData.products.find(el => el.id === product.id);

                if (!found) {
                    this.formData.products.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        count: 1
                    });
                }
            },
            removeProduct(product) {
                this.formData.products = this.formData.products.filter((obj) => obj.id !== product.id);
            },
            submitData(event) {
                // Ensures all fields have data before submitting
                this.formSubmitStarted = true;
                let neededLength = this.regexForTelephone(this.formData.telephone).length;

                // if (!this.validation.created_at.call(this) ||
                //     !this.validation.telephone.call(this) ||
                //     !this.validation.email.call(this) ||
                //     !this.validation.address.call(this) ||
                //     !this.validation.products.call(this)
                // ) {
                //     return;
                // }
                event.target.submit();
            },
            enterAddressInput() {
                if (this.formData.address.length === 0) {
                    console.log('Пустой адрес!');
                    return;
                }
                window.geocode();
                this.addressError = window.addressError ?? "";
                this.addressHint = window.addressHint ?? "";
            },
        }
    })
});

document.addEventListener('DOMContentLoaded', function (e) {
    window.ymaps.ready(init);

    function init() {
        // Подключаем поисковые подсказки к полю ввода.
        let suggestView = new window.ymaps.SuggestView('address');
        let map;
        let placemark;

        window.geocode = geocode;

        function geocode() {
            // Забираем запрос из поля ввода.
            const request = document.getElementById('address').value;
            // Геокодируем введённые данные.
            window.ymaps.geocode(request).then(function (res) {
                let obj = res.geoObjects.get(0);
                let error = "", hint = "";

                if (obj) {
                    // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                    switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        case 'exact':
                            break;
                        case 'number':
                        case 'near':
                        case 'range':
                            error = 'Неточный адрес, требуется уточнение';
                            hint = 'Уточните номер дома';
                            break;
                        case 'street':
                            error = 'Неполный адрес, требуется уточнение';
                            hint = 'Уточните номер дома';
                            break;
                        case 'other':
                        default:
                            error = 'Неточный адрес, требуется уточнение';
                            hint = 'Уточните адрес';
                    }
                } else {
                    error = 'Адрес не найден';
                    hint = 'Уточните адрес';
                }
                window.addressError = error;
                window.addressHint = hint;
                // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                if (error) {
                    showError(error);
                } else {
                    showResult(obj);
                }
            }, function (e) {
                console.log(e)
            })
        }

        function showResult(obj) {
            // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
            document.getElementById('address').classList.remove('input_error');
            document.getElementById('notice').style.display = 'none';

            let mapContainer = document.getElementById('map');
            let bounds = obj.properties.get('boundedBy');
            // Рассчитываем видимую область для текущего положения пользователя.
            let mapState = window.ymaps.util.bounds.getCenterAndZoom(
                bounds,
                [mapContainer.clientWidth, mapContainer.clientHeight]
            );
            // Сохраняем полный адрес для сообщения под картой.
            let address = [obj.getCountry(), obj.getAddressLine()].join(', ');
            // Сохраняем укороченный адрес для подписи метки.
            let shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
            // Убираем контролы с карты.
            mapState.controls = [];
            // Создаём карту.
            createMap(mapState, shortAddress);
        }

        function showError(message) {
            // Удаляем карту.
            if (map) {
                map.destroy();
                map = null;
            }
        }

        function createMap(state, caption) {
            // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
            if (!map) {
                map = new window.ymaps.Map('map', state);
                placemark = new window.ymaps.Placemark(
                    map.getCenter(), {
                        iconCaption: caption,
                        balloonContent: caption
                    }, {
                        preset: 'islands#redDotIconWithCaption'
                    });
                map.geoObjects.add(placemark);
                // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
            } else {
                map.setCenter(state.center, state.zoom);
                placemark.geometry.setCoordinates(state.center);
                placemark.properties.set({iconCaption: caption, balloonContent: caption});
            }
        }
    }
});
