import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "Animehub",
  description: "Animehub",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body className="bg-gradient antialiased">{children}</body>
    </html>
  );
}
