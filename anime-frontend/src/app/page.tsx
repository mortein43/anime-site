"use client";
import { useEffect, useState } from "react";

interface Genre {
  id: string;
  name: string;
  description: string;
}

export default function Home() {
  const [genres, setGenres] = useState<Genre[]>([]);

  useEffect(() => {
    fetch("http://localhost:8000/api/v1/genres")
      .then((res) => res.json())
      .then((data) => {
        setGenres(data.data);
      })
      .catch((err) => {
        console.log("Fetch error:", err);
      });
  }, []);

  return (
    <div className="flex flex-col justify-center items-center">
      <h1>Hello World!</h1>
      <div>
        <ul>
          {genres.map((genre) => (
            <li key={genre.id}>{genre.name}</li>
          ))}
        </ul>
      </div>
    </div>
  );
}
