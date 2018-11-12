<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <input ref="address" id="address" type="text" placeholder="Rechercher une adresse" v-model="address" />

            <div class="google-map" ref="map"></div>

            <div class="inputs">
                <input id="latitude" name="latitude" type="hidden" v-model="latitude" />
                <input id="longitude" name="longitude" type="hidden" v-model="longitude" />
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            map: null,
            autocomplete: null,
            address: null,
            marker: null,
            latitude: null,
            longitude: null,
        }
    },

    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append('latitude', this.latitude);
            formData.append('longitude', this.longitude);
        },

        addMarker(lat, lng) {
            if (this.marker) {
                this.marker.setMap(null);
            }

            const coords = new google.maps.LatLng(lat, lng);

            this.marker = new google.maps.Marker({
                position: coords,
                map: this.map,
                draggable: true
            });

            this.marker.addListener('click', this.removeMarker);
            this.marker.addListener('dragend', this.updateCoords);

            this.map.setCenter(coords);
        },

        updateCoords(event) {
            this.latitude = event.latLng.lat();
            this.longitude = event.latLng.lng();

            this.map.setCenter(event.latLng);
        },

        removeMarker(event) {
            if(confirm('Supprimer la géolocalisation ?')) {
                this.latitude = '';
                this.longitude = '';
                this.marker.setMap(null);
            }
        }
    },

    mounted() {
        const el = this.$refs['map'];
        const options = {
            zoom: this.field.zoom,
            center: new google.maps.LatLng(this.field.lat, this.field.lng)
        }

        this.map = new google.maps.Map(el, options);

        if(this.field.hasCoordinates) {
            this.addMarker(this.field.lat, this.field.lng);
        }

        google.maps.event.addListener(this.map, 'click', (event) => {
            this.latitude = event.latLng.lat();
            this.longitude = event.latLng.lng();

            this.addMarker(this.latitude, this.longitude);
        });

        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.address),
            {
                types: ['geocode'],
                componentRestrictions: {
                    country: 'fr'
                }
            }
        );

        this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace();
            let lat = place.geometry.location.lat();
            let lng = place.geometry.location.lng();

            this.address = place.formatted_address;

            this.latitude = lat;
            this.longitude = lng;
            
            this.addMarker(lat, lng);
        });
    }
}
</script>

<style scoped>
.google-map {
    width: 100%;
    height: 400px;
    margin-bottom: 10px;
}

#address {
    width: 100%;
    border: 1px solid rgb(186,202,214);
    border-radius: 8px;
    padding: 1px 12px;
    height: 36px;
    font-size: 16px;
    color: rgb(123,133,142);
    margin-bottom: 8px;
}
</style>