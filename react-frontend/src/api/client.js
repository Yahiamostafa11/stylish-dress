const API_BASE = import.meta.env.VITE_API_BASE ?? "http://127.0.0.1:8000/api";
const TOKEN_KEY = "styliiiish_token";

function getToken() {
  try {
    return localStorage.getItem(TOKEN_KEY);
  } catch {
    return null;
  }
}

async function handle(res) {
  if (!res.ok) {
    let message = `Request failed: ${res.status}`;
    try {
      const body = await res.json();
      if (body?.message) message = body.message;
    } catch {
      // ignore non-JSON error bodies
    }
    const error = new Error(message);
    error.status = res.status;
    throw error;
  }
  return res.json();
}

function get(path, { auth = false } = {}) {
  const headers = {};
  if (auth) headers.Authorization = `Bearer ${getToken() ?? ""}`;
  return fetch(`${API_BASE}${path}`, { headers }).then(handle);
}

function postJson(path, data, { auth = false } = {}) {
  const headers = { "Content-Type": "application/json" };
  if (auth) headers.Authorization = `Bearer ${getToken() ?? ""}`;
  return fetch(`${API_BASE}${path}`, {
    method: "POST",
    headers,
    body: JSON.stringify(data),
  }).then(handle);
}

function postForm(path, formData, { auth = false } = {}) {
  const headers = {};
  if (auth) headers.Authorization = `Bearer ${getToken() ?? ""}`;
  return fetch(`${API_BASE}${path}`, {
    method: "POST",
    headers,
    body: formData,
  }).then(handle);
}

// ---- catalog ----

export function fetchProducts({ search = "", category = "", limit = 40 } = {}) {
  const params = new URLSearchParams();
  if (search) params.set("search", search);
  if (category) params.set("category", category);
  if (limit) params.set("limit", String(limit));
  return get(`/products?${params.toString()}`).then((r) => r.data);
}

export function fetchProduct(id) {
  return get(`/products/${id}`).then((r) => r.data);
}

export function fetchCategories() {
  return get("/categories").then((r) => r.data);
}

// ---- auth ----

export function registerAccount({ name, email, password, phone }) {
  return postJson("/auth/register", { name, email, password, phone });
}

export function loginAccount({ email, password }) {
  return postJson("/auth/login", { email, password });
}

export function fetchMe() {
  return get("/auth/me", { auth: true }).then((r) => r.user);
}

// ---- amnahi ----

export function fetchAmnahiListings({ limit = 30 } = {}) {
  const params = new URLSearchParams();
  if (limit) params.set("limit", String(limit));
  return get(`/amnahi/listings?${params.toString()}`).then((r) => r.data);
}

export function fetchAmnahiListing(id) {
  return get(`/amnahi/listings/${id}`).then((r) => r.data);
}

export function fetchMyAmnahiListings() {
  return get("/amnahi/mine", { auth: true }).then((r) => r.data);
}

export function createAmnahiListing({ title, description, price, dressType, images }) {
  const formData = new FormData();
  formData.append("title", title);
  formData.append("description", description ?? "");
  formData.append("price", price);
  formData.append("dress_type", dressType ?? "");
  images.forEach((file) => formData.append("images[]", file));
  return postForm("/amnahi/listings", formData, { auth: true }).then((r) => r.data);
}

export function expressInterest(listingId) {
  return postJson(`/amnahi/listings/${listingId}/interest`, {}, { auth: true }).then((r) => r.data);
}

// ---- reviews ----

export function submitReview({ name, message }) {
  return postJson("/reviews", { name, message });
}

// ---- checkout ----

export function submitCheckout({ customer, items }) {
  return postJson("/checkout", { customer, items });
}

// ---- chat ----

export function fetchConversations() {
  return get("/conversations", { auth: true }).then((r) => r.data);
}

export function fetchMessages(conversationId) {
  return get(`/conversations/${conversationId}/messages`, { auth: true });
}

export function sendMessage(conversationId, body) {
  return postJson(`/conversations/${conversationId}/messages`, { body }, { auth: true }).then((r) => r.data);
}
