import React, {useEffect, useState} from 'react';
import axios from 'axios';
import Pagination from "react-js-pagination";
import queryString from 'query-string'
import LoadingCircle from "./LoadingCircle";

export default function HistoryList() {
    const [loading, setLoading] = useState(false);
    const [entries, setEntries] = useState([]);
    const [activePage, setActivePage] = useState(1);
    const [totalItemsCount, setTotalItemsCount] = useState(0);

    useEffect(() => {
        getUsers(activePage);
    }, []);

    const getUsers = (params) => {
        setLoading(true);

        axios.get(`/api/history_entries?page=` + params).then(response => {
            setActivePage(parseInt(queryString.parseUrl(response.data['hydra:view']['@id']).query.page));
            setEntries(response.data['hydra:member']);
            setTotalItemsCount(response.data['hydra:totalItems']);
            setLoading(false);
        })
    }

    return (
        <div>
            <section className="row-section">
                <div>
                    <div className="text-center mt-3 mb-3">
                        <Pagination
                            activePage={activePage}
                            itemsCountPerPage={10}
                            totalItemsCount={totalItemsCount}
                            onChange={getUsers}
                        />
                    </div>
                    {loading ? (
                        <div style={{margin: "0 auto", width: '30px', height: "100px"}} className="mt-5">
                            <LoadingCircle/>
                        </div>
                    ) : (
                        <div>
                            <table className="table table-dark">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Latitude</th>
                                    <th scope="col">Longitude</th>
                                    <th scope="col">Temperature</th>
                                    <th scope="col">Clouds</th>
                                    <th scope="col">Wind</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Created at</th>
                                </tr>
                                </thead>
                                <tbody>
                                {entries.map(entry => {
                                    let date = new Date(Date.parse(entry.createdAt));
                                    return <tr key={entry.id}>
                                            <td scope="row">{entry.id}</td>
                                            <td scope="row">{entry.city}</td>
                                            <td scope="row">{entry.latitude}</td>
                                            <td scope="row">{entry.longitude}</td>
                                            <td scope="row">{entry.temperature}</td>
                                            <td scope="row">{entry.cloud}</td>
                                            <td scope="row">{entry.wind}</td>
                                            <td scope="row">{entry.description}</td>
                                            <td scope="row">{date.toLocaleDateString("en-US") +' ' + date.toLocaleTimeString("en-US")}</td>
                                        </tr>;
                                    }
                                )}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </section>
        </div>
    )
}
