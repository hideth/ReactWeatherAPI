import React, {useEffect, useState} from 'react';
import axios from 'axios';
import LoadingCircle from "./LoadingCircle";

export default function Statistics() {
    const [loading, setLoading] = useState(true);
    const [stats, setStats] = useState([]);

    const getStats = () => {
        axios.get('/api/weather/statistics').then(response => {
            setLoading(false);
            setStats(response.data)
        });
    }
    useEffect(() => {
        getStats();
    }, []);

    return (
        <div>
            {loading ?
                <div style={{margin: "0 auto", width: '30px', height: "100px"}}>
                    <LoadingCircle/>
                </div>
                :
                <div>
                    <table className="table table-dark">
                        <thead>
                        <tr>
                            <th scope="col">Count requests</th>
                            <th scope="col">AVG Temperature</th>
                            <th scope="col">MAX Temperature</th>
                            <th scope="col">MIN Temperature</th>
                            <th scope="col">Most searched city</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{stats.COUNT}</td>
                            <td>{stats.AVG_TEMPERATURE}</td>
                            <td>{stats.MAX_TEMPERATURE}</td>
                            <td>{stats.MIN_TEMPERATURE}</td>
                            <td>{stats.MOST_SEARCHED_CITY}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            }
        </div>
    )
}
