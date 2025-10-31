import Image from "next/image";
import styles from "./logo.module.css";

export default function Logo() {
  return (
    <Image
      src="/assets/logo.webp"
      alt="Animehub"
      width={100}
      height={100}
      className={styles.logo}
      priority
    />
  );
}
