import React, { useState, useEffect } from 'react';
import axios from 'axios';

const TodoApp = () => {
    const [todos, setTodos] = useState([]);
    const [newTodo, setNewTodo] = useState({ title: '', details: '', status: 'not_started' });
    const [filter, setFilter] = useState('');
    const [search, setSearch] = useState('');
    const [sortBy, setSortBy] = useState('created_at');
    const [sortOrder, setSortOrder] = useState('desc');

    useEffect(() => {
        fetchTodos();
    }, [filter, search, sortBy, sortOrder]);

    const fetchTodos = async () => {
        try {
            const params = new URLSearchParams();
            if (filter) params.append('status', filter);
            if (search) params.append('search', search);
            params.append('sort_by', sortBy);
            params.append('sort_order', sortOrder);

            const response = await axios.get(`/api/todos?${params.toString()}`);
            setTodos(response.data.data || []);
        } catch (error) {
            console.error('Error fetching todos:', error);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post('/api/todos', newTodo);
            setNewTodo({ title: '', details: '', status: 'not_started' });
            fetchTodos();
        } catch (error) {
            console.error('Error creating todo:', error);
        }
    };

    const handleUpdate = async (id, updatedTodo) => {
        try {
            await axios.put(`/api/todos/${id}`, updatedTodo);
            fetchTodos();
        } catch (error) {
            console.error('Error updating todo:', error);
        }
    };

    const handleDelete = async (id) => {
        try {
            await axios.delete(`/api/todos/${id}`);
            fetchTodos();
        } catch (error) {
            console.error('Error deleting todo:', error);
        }
    };

    return (
        <div className="container mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold mb-8">Todo List</h1>

            {/* Filters */}
            <div className="mb-6 flex flex-wrap gap-4">
                <input
                    type="text"
                    placeholder="Search todos..."
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    className="p-2 border rounded"
                />
                <select
                    value={filter}
                    onChange={(e) => setFilter(e.target.value)}
                    className="p-2 border rounded"
                >
                    <option value="">All Status</option>
                    <option value="not_started">Not Started</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                <select
                    value={sortBy}
                    onChange={(e) => setSortBy(e.target.value)}
                    className="p-2 border rounded"
                >
                    <option value="created_at">Sort by Date</option>
                    <option value="title">Sort by Title</option>
                    <option value="status">Sort by Status</option>
                </select>
                <select
                    value={sortOrder}
                    onChange={(e) => setSortOrder(e.target.value)}
                    className="p-2 border rounded"
                >
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>

            {/* Add Todo Form */}
            <form onSubmit={handleSubmit} className="mb-8 p-4 bg-gray-100 rounded">
                <div className="flex flex-wrap gap-4">
                    <input
                        type="text"
                        placeholder="Todo title"
                        value={newTodo.title}
                        onChange={(e) => setNewTodo({ ...newTodo, title: e.target.value })}
                        className="p-2 border rounded flex-1"
                        required
                    />
                    <input
                        type="text"
                        placeholder="Details (optional)"
                        value={newTodo.details}
                        onChange={(e) => setNewTodo({ ...newTodo, details: e.target.value })}
                        className="p-2 border rounded flex-1"
                    />
                    <select
                        value={newTodo.status}
                        onChange={(e) => setNewTodo({ ...newTodo, status: e.target.value })}
                        className="p-2 border rounded"
                    >
                        <option value="not_started">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Add Todo
                    </button>
                </div>
            </form>

            {/* Todo List */}
            <div className="space-y-4">
                {todos.map((todo) => (
                    <div key={todo.id} className="p-4 border rounded shadow-sm hover:shadow">
                        <div className="flex justify-between items-start">
                            <div>
                                <h3 className="font-semibold">{todo.title}</h3>
                                <p className="text-gray-600">{todo.details}</p>
                                <select
                                    value={todo.status}
                                    onChange={(e) => handleUpdate(todo.id, { ...todo, status: e.target.value })}
                                    className="mt-2 p-1 border rounded text-sm"
                                >
                                    <option value="not_started">Not Started</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <button
                                onClick={() => handleDelete(todo.id)}
                                className="text-red-500 hover:text-red-700"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default TodoApp;
