<?php

namespace App\Controller;

use App\Entity\OAuth2ClientProfile;
use App\Entity\OAuth2UserConsent;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class IndexController extends AbstractController
{

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/api/test', name: 'app_api_test')]
    public function apiTest(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('.well-known/jwks.json', name: 'app_jwks', methods: ['GET'])]
    public function jwks(): Response
    {
        // Load the public key from the filesystem and use OpenSSL to parse it.
        $kernelDirectory = $this->getParameter('kernel.project_dir');
        $publicKey = openssl_pkey_get_public(file_get_contents($kernelDirectory . '/var/keys/public.key'));
        $details = openssl_pkey_get_details($publicKey);
        $jwks = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'alg' => 'RS256',
                    'use' => 'sig',
                    'kid' => '1',
                    'n' => strtr(rtrim(base64_encode($details['rsa']['n']), '='), '+/', '-_'),
                    'e' => strtr(rtrim(base64_encode($details['rsa']['e']), '='), '+/', '-_'),
                ],
            ],
        ];
        return $this->json($jwks);
    }

    #[Route('.well-known/openid-configuration', name: 'app_openid', methods: ['GET'])]
    public function config(): Response
    {
        $issuer = "http://localhost:8000";

        $authorizationEndpoint = $this->generateUrl('oauth2_authorize', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $tokenEndpoint = $this->generateUrl('oauth2_token', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $jwksUri = $this->generateUrl('app_jwks', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $userinfoEndpoint = $issuer . '/api/userinfo';

        $config = [
            'issuer' => $issuer,
            'authorization_endpoint' => $authorizationEndpoint,
            'token_endpoint' => $tokenEndpoint,
            'userinfo_endpoint' => $userinfoEndpoint,
            'jwks_uri' => $jwksUri,

            // This IDP uses asymmetric signing (RS256) for tokens.
            'id_token_signing_alg_values_supported' => ['RS256'],
            'subject_types_supported' => ["public"],

            // OAuth 2.0 / OIDC compatible capabilities
            'response_types_supported' => ['code'],
            'grant_types_supported' => ['authorization_code'],
            'token_endpoint_auth_methods_supported' => ['private_key_jwt'],
            // If/when PKCE is implemented, add: 'code_challenge_methods_supported' => ['S256', 'plain']

            // Scopes/claims the provider can work with. These are indicative; applications may define custom scopes.
            'scopes_supported' => ['openid', 'email', 'profile', 'offline_access'],
            'claims_supported' => [
                'sub',
                'email',
                'app_id',
                'account_id',
                'iss',
                'aud',
                'exp',
                'iat',
                'nbf',
                'jti'
            ],

            "token_endpoint_auth_signing_alg_values_supported" => ["RS256"],
            "request_object_signing_alg_values_supported" => ["RS256"],
            'response_modes_supported' => ['query', 'form_post'],
        ];

        $response = new JsonResponse($config);
        $response->setPublic();
        $response->setMaxAge(300);
        $response->setSharedMaxAge(300);

        return $response;
    }
}
