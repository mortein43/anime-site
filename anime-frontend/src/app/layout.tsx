import type { Metadata } from "next";
import "./globals.css";
import Header from "@/components/Header/Header";
import { Inter } from "next/font/google";

export const metadata: Metadata = {
  title: "Animehub",
  description: "Animehub",
};

const inter = Inter({
  subsets: ["latin", "cyrillic"],
  variable: "--font-inter",
  display: "swap",
});

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={inter.variable}>
      <body className="bg-gradient text-white font-sans antialiased">
        <Header />
        <main>{children}</main>
      </body>
    </html>
  );
}
