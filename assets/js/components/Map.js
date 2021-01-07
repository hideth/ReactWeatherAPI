import React, { useState } from 'react';
import GoogleMapReact from 'google-map-react';
import axios from 'axios';
import ReactLoading from 'react-loading';
import { useAlert } from "react-alert";

const defaultProps = {
  center: {
    lat: 52.31,
    lng: 15.01,
  },
  zoom: 3,
  apiKey: 'AIzaSyBRjeVSxqw12aExgf0sZvfXoGRJERqIlMs',
};
export default function Map() {
  const [loading, setLoading] = useState(false);
  const alert = useAlert();
  const request = (props) => {
    let params = {
      lat: props.lat,
      lng: props.lng,
    }
    let url = '/api/weather/add';
    setLoading(true);
    axios.post(url, params, {
      "headers": {
        "content-type": "application/json",
      },

    })
      .then(function (response) {
        setLoading(false);
        console.log(response);
        alert.success('New entry for '+ response.data.city + ' has been added!');
      })
      .catch(function () {
        alert.error('Location unavailable!');
        setLoading(false);
      })
  }

  return (
    <div style={{ height: '100vh', width: '100%' }}>
      <div style={{ position: 'absolute', top: '50%', left: '50%', zIndex: 10 }}>
        {loading ? <ReactLoading color='black' /> : <div></div>}
      </div>
      <GoogleMapReact
        bootstrapURLKeys={{ key: defaultProps.apiKey }}
        defaultCenter={defaultProps.center}
        defaultZoom={defaultProps.zoom}
        onClick={request}
      >
      </GoogleMapReact>
    </div>
  )
}
