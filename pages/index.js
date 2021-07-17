import Head from "next/head";
import styles from "../styles/Home.module.css";

export default function Home() {
  return (
    <div className={styles.container}>
      <Head>
        <title>Gulay Mart</title>
        <meta
          name="description"
          content="Gulay Mart is your neighborhood veggies supplier."
        />
        <link rel="icon" href="/favicon.ico" />
        <link rel="manifest" href="/manifest.webmanifest.json" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      </Head>

      <h1 className={styles.title}>Gulay Mart</h1>
      <p className={styles.summary}>
        Your neighborhood veggies supplier
        <br /><br />
        <img src="favicon-192.png" className="{styles.image}" />
      </p>

      <footer className={styles.footer}>
        <p className={styles.description}>
        </p>
      </footer>
    </div>
  );
}
