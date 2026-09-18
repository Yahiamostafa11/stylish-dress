import { Routes, Route } from "react-router-dom";
import Header from "./components/Header";
import Footer from "./components/Footer";
import TipsWidget from "./components/TipsWidget";
import Home from "./pages/Home";
import Measurements from "./pages/Measurements";
import Shop from "./pages/Shop";
import Rent from "./pages/Rent";
import Marketplace from "./pages/Marketplace";
import AmnahiHome from "./pages/AmnahiHome";
import AmnahiListing from "./pages/AmnahiListing";
import Sell from "./pages/Sell";
import About from "./pages/About";
import Contact from "./pages/Contact";
import Wishlist from "./pages/Wishlist";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Conversations from "./pages/Conversations";
import ConversationThread from "./pages/ConversationThread";
import Cart from "./pages/Cart";
import Checkout from "./pages/Checkout";
import ProductDetail from "./pages/ProductDetail";
import PrivacyPolicy from "./pages/PrivacyPolicy";
import TermsConditions from "./pages/TermsConditions";
import RefundPolicy from "./pages/RefundPolicy";
import ShippingPolicy from "./pages/ShippingPolicy";
import CookiePolicy from "./pages/CookiePolicy";
import MarketplacePolicy from "./pages/MarketplacePolicy";
import Faq from "./pages/Faq";
import Reviews from "./pages/Reviews";

export default function App() {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/product/:id" element={<ProductDetail />} />
        <Route path="/order/:productId/measurements" element={<Measurements />} />
        <Route path="/shop" element={<Shop />} />
        <Route path="/rent" element={<Rent />} />
        <Route path="/marketplace" element={<Marketplace />} />
        <Route path="/amnahi" element={<AmnahiHome />} />
        <Route path="/amnahi/:id" element={<AmnahiListing />} />
        <Route path="/sell" element={<Sell />} />
        <Route path="/about" element={<About />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/reviews" element={<Reviews />} />
        <Route path="/wishlist" element={<Wishlist />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/messages" element={<Conversations />} />
        <Route path="/messages/:id" element={<ConversationThread />} />
        <Route path="/cart" element={<Cart />} />
        <Route path="/checkout" element={<Checkout />} />
        <Route path="/privacy-policy" element={<PrivacyPolicy />} />
        <Route path="/terms-conditions" element={<TermsConditions />} />
        <Route path="/refund-policy" element={<RefundPolicy />} />
        <Route path="/shipping-policy" element={<ShippingPolicy />} />
        <Route path="/cookie-policy" element={<CookiePolicy />} />
        <Route path="/marketplace-policy" element={<MarketplacePolicy />} />
        <Route path="/faq" element={<Faq />} />
      </Routes>
      <Footer />
      <TipsWidget />
    </>
  );
}
