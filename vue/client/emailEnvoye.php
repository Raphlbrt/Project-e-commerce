<h2>Inscription réussie</h2>
<p>
    <?php

    use App\MDS\Lib\VerificationEmail;
    /** @var Client $client */
    VerificationEmail::envoiEmailValidation($client);
        ?>
</p>
