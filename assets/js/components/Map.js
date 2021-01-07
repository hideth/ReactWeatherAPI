import React, { Component } from 'react';
import GoogleMapReact from 'google-map-react';
import axios from 'axios';
import ReactLoading from 'react-loading';

const defaultProps = {
  center: {
    lat: 52.31,
    lng: 15.01,
  },
  zoom: 3,
  apiKey: 'AIzaSyBRjeVSxqw12aExgf0sZvfXoGRJERqIlMs',
};

class SimpleMap extends Component {
  constructor() {
    super();
    this.state = { loading: false }
    this.request = this.request.bind(this);
  }
  
  request(props) {
    let params = {
      lat: props.lat,
      lng: props.lng,
    }
    let url = '/api/weather/add';
    this.setState({ loading: true });
    var self = this;
    axios.post(url, params, {
      "headers": {
        "content-type": "application/json",
      },

    })
      .then(function (response) {
        self.setState({ loading: false });
        console.log(response);
      })
  }

  render() {
    return (
      <div style={{ height: '100vh', width: '100%' }}>
        <div style={{ position: 'absolute', top: '50%', left: '50%', zIndex: 10 }}>
          {this.state.loading ? <ReactLoading color='black' /> : <div></div>}
        </div>
        <GoogleMapReact
          bootstrapURLKeys={{ key: defaultProps.apiKey }}
          defaultCenter={defaultProps.center}
          defaultZoom={defaultProps.zoom}
          onClick={this.request}
        >
        </GoogleMapReact>
      </div>
    );
  }
}

export default SimpleMap;