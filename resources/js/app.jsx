import './bootstrap';
import '../css/app.css';
import React from 'react';
import ReactDOM from 'react-dom/client';
import TodoApp from './components/TodoApp';

ReactDOM.createRoot(document.getElementById('app')).render(
    <React.StrictMode>
        <TodoApp />
    </React.StrictMode>
);
