import Head from "next/head";
import styles from "../styles/Home.module.css";
import Image from 'next/image';

export default function Home() {
  return (
    <div className={styles.container}>
      <Head>
        <title>Gulay Mart</title>
        <meta
          name="description"
          content="Gulay Mart is your neighborhood veggies supplier."
        />
        <link rel="icon" href="/gmicon.ico" />
        <link rel="manifest" href="/manifest.webmanifest.json" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      </Head>

      <h1 className={styles.title}>Gulay Mart</h1>
      <p className={styles.summary}>
        Your neighborhood veggies supplier
        <br /><br />
        <Image src="/favicon-192.png" className="{styles.image}" alt="Gulay Mart Logo" width="192" height="192" />
      </p>

      <footer className={styles.footer}>
        <p className={styles.description}> </p>
      </footer>
    </div>
  );
}
