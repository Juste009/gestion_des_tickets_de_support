<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un ticket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div>

        <h1>Créer un ticket de support</h1>

        <p>
            Signalez votre problème informatique en remplissant le formulaire ci-dessous.
        </p>

        <div>

            <div>

                <form action="index.php" method="POST">

                    <h4>Informations utilisateur</h4>

                    <div>

                        <div>
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
                        </div>

                        <div>
                            <label for="prenom">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                        </div>

                    </div>

                    <div>

                        <div>
                            <label for="email">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="exemple@email.com" required>
                        </div>

                        <div>
                            <label for="service">Service</label>
                            <input type="text" class="form-control" id="service" name="service" placeholder="Votre service" required>
                        </div>

                    </div>

                    <hr>

                    <h4>Informations sur le problème</h4>

                    <div>
                        <label for="sujet">Objet du ticket</label>
                        <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Ex : Mon ordinateur ne démarre plus" required>
                    </div>

                    <div>

                        <div>
                            <label for="categorie">Catégorie</label>

                            <select class="form-select" id="categorie" name="categorie" required>
                                <option value="" selected disabled>-- Choisir une catégorie --</option>
                                <option value="Matériel">Matériel</option>
                                <option value="Logiciel">Logiciel</option>
                                <option value="Réseau">Réseau</option>
                                <option value="Messagerie">Messagerie</option>
                                <option value="Compte utilisateur">Compte utilisateur</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="priorite">Priorité</label>

                            <select class="form-select" id="priorite" name="priorite" required>
                                <option value="" selected disabled>-- Choisir une priorité --</option>
                                <option value="Basse">Basse</option>
                                <option value="Normale">Normale</option>
                                <option value="Haute">Haute</option>
                                <option value="Critique">Critique</option>
                            </select>
                        </div>

                    </div>

                    <div>
                        <label for="description">Description du problème</label>

                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="6"
                            placeholder="Décrivez votre problème en détail..."
                            required></textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            Envoyer le ticket
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

    

</body>

</html>