import React, { Component } from 'react';
import GoogleMapReact from 'google-map-react';
import axios from 'axios';

const defaultProps = {
  center: {
    lat: 52.31,
    lng: 15.01,
  },
  zoom: 3,
  apiKey: 'AIzaSyBRjeVSxqw12aExgf0sZvfXoGRJERqIlMs',
};

function request(lat, lng) {
  let params = {
    lat: lat,
    lng: lng,
  }
  let url = '/api/weather/add';
  axios.post(url, params, {
    "headers": {

      "content-type": "application/json",

    },

  })
    .then(function (response) {
      console.log(response);
    })
}
const _onClick = ({ x, y, lat, lng, event }) => request(lat, lng)
class SimpleMap extends Component {
  constructor() {
    super();

    this.state = { posts: [], loading: true }
  }

  render() {
    return (
      <div style={{ height: '100vh', width: '100%' }}>
        <GoogleMapReact
          bootstrapURLKeys={{ key: defaultProps.apiKey }}
          defaultCenter={defaultProps.center}
          defaultZoom={defaultProps.zoom}
          onClick={_onClick}
        >
        </GoogleMapReact>
      </div>
    );
  }
}

export default SimpleMap;