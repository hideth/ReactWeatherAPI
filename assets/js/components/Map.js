import React, { Component } from 'react';
import GoogleMapReact from 'google-map-react';

const AnyReactComponent = ({ text }) => <div>{text}</div>;

const defaultProps = {
  center: {
    lat: 52.31,
    lng: 15.01,
  },
  zoom: 3,
};

const _onClick = ({x, y, lat, lng, event}) => console.log(x, y, lat, lng, event)
class SimpleMap extends Component {
  render() {
    return (
      <div style={{ height: '100vh', width: '100%' }}>
        <GoogleMapReact
          bootstrapURLKeys={{ key:'AIzaSyBRjeVSxqw12aExgf0sZvfXoGRJERqIlMs' }}
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