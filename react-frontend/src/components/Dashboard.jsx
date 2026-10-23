import { useEffect, useState } from "react";
import "./Dashboard.css";
import axios from "axios";

// Use "/api/v1/products/queries" if your frontend already has an API proxy.
const API_URL =
  "http://127.0.0.1:8000/api/v1/products/queries";

const currency = (value) =>
  new Intl.NumberFormat("en-PH", {
    style: "currency",
    currency: "PHP",
  }).format(Number(value ?? 0));

const queryOptions = [
  ["name_and_price", "Product names and prices"],
  ["products_with_category", "Products with categories"],
  ["products_ordered_by_price", "Products ordered by price"],
  ["first_five_products", "First five products"],
  [
    "price_greater_than_or_equal",
    "Products with price ≥ ₱3",
  ],
  ["electronics", "Electronics products"],
  ["total_price_per_category", "Total prices per category"],
  ["average_price_per_category", "Average prices per category"],
  ["lowest_price_per_category", "Lowest prices per category"],
  ["average_price_sorted", "Category averages: low to high"],
  ["electronics_and_books", "Electronics and Books averages"],
];

function unwrapResponse(response) {
  let result = response;

  // Supports response wrappers such as:
  // { status: true, data: { ...queryResults } }
  for (let i = 0; i < 4; i++) {
    if (
      result &&
      !Array.isArray(result) &&
      Object.prototype.hasOwnProperty.call(result, "data")
    ) {
      result = result.data;
    } else {
      break;
    }
  }

  return result;
}

function formatHeading(key) {
  return key
    .replaceAll("_", " ")
    .replace(/\b\w/g, (letter) => letter.toUpperCase());
}

function formatCell(key, value) {
  if (value === null || value === undefined) {
    return "—";
  }

  if (
    ["price", "total_price", "avg_price", "min_price"].includes(key)
  ) {
    return currency(value);
  }

  if (typeof value === "object") {
    if (key === "category") {
      return value.name || "Uncategorized";
    }

    return JSON.stringify(value);
  }

  return String(value);
}

function QueryTable({ rows }) {
  if (!rows.length) {
    return (
      <div className="qd-empty">
        No results found for this query.
      </div>
    );
  }

  // Show the union of columns, even if some rows contain null values.
  const columns = [
    ...new Set(rows.flatMap((row) => Object.keys(row))),
  ];

  return (
    <div className="qd-table-wrapper">
      <table>
        <thead>
          <tr>
            {columns.map((column) => (
              <th key={column}>
                {formatHeading(column)}
              </th>
            ))}
          </tr>
        </thead>

        <tbody>
          {rows.map((row, index) => (
            <tr key={row.id ?? index}>
              {columns.map((column) => (
                <td key={column}>
                  {formatCell(column, row[column])}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default function QueriesDashboard() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [refresh, setRefresh] = useState(0);

  const [selectedQuery, setSelectedQuery] =
    useState("name_and_price");

  const [search, setSearch] = useState("");

  useEffect(() => {
  const controller = new AbortController();

  async function loadQueries() {
    setLoading(true);
    setError("");

    try {
      const response = await axios.get(API_URL, {
        signal: controller.signal,
        headers: {
          Accept: "application/json",
        },
      });

      const result = response.data;

      if (result.status === false || result.success === false) {
        throw new Error(
          result.message || "Unable to load query results."
        );
      }

      const queryData = unwrapResponse(result);

      if (
        !queryData ||
        !Object.prototype.hasOwnProperty.call(
          queryData,
          "total_price"
        )
      ) {
        throw new Error(
          "Unexpected response. Check your queries endpoint."
        );
      }

      if (!controller.signal.aborted) {
        setData(queryData);
      }
    } catch (error) {
      if (!axios.isCancel(error)) {
        setError(
          error.response?.data?.message ||
            error.message ||
            "Unable to connect to Laravel."
        );
      }
    } finally {
      if (!controller.signal.aborted) {
        setLoading(false);
      }
    }
  }

  loadQueries();

  return () => controller.abort();
}, [refresh]);

  const summaryCards = [
    {
      title: "Total product prices",
      value: data?.total_price,
      description: "Sum of all unit prices",
      color: "purple",
    },
    {
      title: "Average price",
      value: data?.average_price,
      description: "Average price per product",
      color: "blue",
    },
    {
      title: "Highest price",
      value: data?.highest_price,
      description: "Most expensive product price",
      color: "green",
    },
    {
      title: "Lowest price",
      value: data?.lowest_price,
      description: "Least expensive product price",
      color: "orange",
    },
  ];

  const categoryTotals = data?.total_price_per_category || [];

  const highestTotal = Math.max(
    1,
    ...categoryTotals.map((category) =>
      Number(category.total_price)
    )
  );

  const selectedLabel =
    queryOptions.find(([key]) => key === selectedQuery)?.[1];

  // Also accepts nested Laravel resource collections.
  const queryValue = unwrapResponse(data?.[selectedQuery]);

  const rows = Array.isArray(queryValue) ? queryValue : [];

  // This searches the selected query's complete returned result.
  const filteredRows = rows.filter((row) =>
    JSON.stringify(row)
      .toLowerCase()
      .includes(search.trim().toLowerCase())
  );

  return (
    <main className="query-dashboard">
      <header className="qd-header">
        <div>
          <span className="qd-eyebrow">
            PRODUCT ANALYTICS
          </span>

          <h1>Dashboard</h1>

          <p>
            View product statistics and results from your
            Laravel queries.
          </p>
        </div>

        <button
          className="qd-refresh"
          disabled={loading}
          onClick={() => setRefresh((value) => value + 1)}
        >
          {loading ? "Loading…" : "Refresh data"}
        </button>
      </header>

      {error && (
        <div className="qd-error" role="alert">
          <span>{error}</span>

          <button
            disabled={loading}
            onClick={() => setRefresh((value) => value + 1)}
          >
            Retry
          </button>
        </div>
      )}

      <section
        className="qd-summary"
        aria-label="Product price statistics"
        aria-busy={loading}
      >
        {summaryCards.map((card) => (
          <article
            className={`qd-card qd-${card.color}`}
            key={card.title}
          >
            <span>{card.title}</span>

            <strong>
              {loading
                ? "…"
                : error || card.value === undefined
                  ? "—"
                  : currency(card.value)}
            </strong>

            <small>{card.description}</small>
          </article>
        ))}
      </section>

      <section className="qd-panel">
        <div className="qd-section-heading">
          <div>
            <h2>Category price comparison</h2>
            <p>Sum of unit prices for each category.</p>
          </div>

          <span className="qd-count">
            {categoryTotals.length} categories
          </span>
        </div>

        {loading ? (
          <div className="qd-empty">
            Loading category statistics…
          </div>
        ) : error ? (
          <div className="qd-empty">
            Category statistics unavailable.
          </div>
        ) : categoryTotals.length === 0 ? (
          <div className="qd-empty">
            No category statistics found.
          </div>
        ) : (
          <div className="qd-bars">
            {categoryTotals.map((category, index) => (
              <div
                className="qd-bar-row"
                key={`${category.name}-${index}`}
              >
                <div className="qd-bar-heading">
                  <span>{category.name}</span>

                  <strong>
                    {currency(category.total_price)}
                  </strong>
                </div>

                <div className="qd-bar-track">
                  <div
                    className="qd-bar-fill"
                    style={{
                      width: `${
                        (Number(category.total_price) /
                          highestTotal) *
                        100
                      }%`,
                    }}
                  />
                </div>
              </div>
            ))}
          </div>
        )}
      </section>

      <section className="qd-panel qd-results">
        <div className="qd-section-heading">
          <div>
            <h2>Query results</h2>
            <p>Select a query to inspect its returned data.</p>
          </div>
        </div>

        <div className="qd-controls">
          <label>
            Query

            <select
              value={selectedQuery}
              onChange={(event) => {
                setSelectedQuery(event.target.value);
                setSearch("");
              }}
            >
              {queryOptions.map(([key, label]) => (
                <option key={key} value={key}>
                  {label}
                </option>
              ))}
            </select>
          </label>

          <label>
            Search results

            <input
              type="search"
              placeholder="Search this query…"
              value={search}
              onChange={(event) =>
                setSearch(event.target.value)
              }
            />
          </label>
        </div>

        <div className="qd-result-heading">
          <h3>{selectedLabel}</h3>

          {!loading && !error && (
            <span>
              {filteredRows.length} of {rows.length} results
            </span>
          )}
        </div>

        {loading ? (
          <div className="qd-empty">
            Loading query results…
          </div>
        ) : error ? (
          <div className="qd-empty">
            Query results unavailable. Retry the connection.
          </div>
        ) : (
          <QueryTable rows={filteredRows} />
        )}
      </section>

      <footer className="qd-footer">
        React frontend · Laravel backend · Prices in PHP
      </footer>
    </main>
  );
}