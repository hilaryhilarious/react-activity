import {useState, useEffect} from 'react';

export default function Counter() {
    const [count, setCount] = useState(0);

    useEffect(() => {
        document.title = `Count: ${count}`;
    }, [count]); //dependency array - only run when count changes

    return (
        <button onClick={() => setCount(count + 1)}>
        Count: {count}
        </button>

    );
}
