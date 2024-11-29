import { prisma } from "../prisma";
import { Elysia } from "elysia";

interface Presenca {
    ref_pessoa: number,
    ref_inscricao_evento: number
}

export const presencasRoutes = (app: Elysia) => {
    app
        .get('/presencas', async () => {
            return await prisma.presencas.findMany();
        })
        .get('/presencas/:id', async ({ params }) => {
            const id = parseInt(params.id);

            return await prisma.presencas.findUnique({
                where: { id }
            });
        })
        .post('/presencas', async ({ body }) => {
            try {
                const { ref_pessoa, ref_inscricao_evento } = body as Presenca;

                const presenca = await prisma.presencas.create({
                    data: { ref_pessoa, ref_inscricao_evento }
                });

                // Convertendo BigInt para string
                return {
                    ...presenca,
                    id: presenca.id.toString(),
                    ref_inscricao_evento: presenca.ref_inscricao_evento.toString(),
                    ref_pessoa: presenca.ref_pessoa.toString()
                };
            } catch (error) {
                console.error('Erro ao salvar presença:', error);
                return { error: 'Erro ao salvar presença', details: error };
            }
        })
        .put('/presencas/:id', async ({ params, body }) => {
            const id = parseInt(params.id);
            const { ref_pessoa, ref_inscricao_evento } = body as Presenca;

            const presenca = await prisma.presencas.update({
                where: { id },
                data: { ref_pessoa, ref_inscricao_evento }
            });

            return {
                ...presenca,
                id: presenca.id.toString(),
                ref_inscricao_evento: presenca.ref_inscricao_evento.toString(),
                ref_pessoa: presenca.ref_pessoa.toString()
            }
        })
        .delete('/presencas/:id', async ({ params }) => {
            const id = parseInt(params.id);
            return await prisma.presencas.delete({
                where: { id },
            })
        })
}