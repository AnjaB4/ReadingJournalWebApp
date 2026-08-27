<?php
/** @var array $requests */
/** $requests je niz asocijativnih nizova sa podacima korisnika i statusom zahteva */

?>

<div class="card mb-4">
    <div class="card-header pb-0">
        <h6>Pending Librarian Requests</h6>
    </div>
    <div class="card-body px-0 pt-0 pb-1">
        <div class="table-responsive p-2 ps-3">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">User</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Email</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Status</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">
                                    <?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']); ?>
                                </p>
                            </td>
                            <td>
                                <p class="text-sm text-secondary mb-0"><?= htmlspecialchars($req['email']); ?></p>
                            </td>
                            <td>
                                    <span class="badge badge-sm <?= $req['status'] === 'pending' ? 'bg-warning' : ($req['status'] === 'Approved' ? 'bg-success' : 'bg-danger') ?>">
                                        <?= htmlspecialchars($req['status']); ?>
                                    </span>
                            </td>
                            <td>
                                <?php if ($req['status'] === 'pending'): ?>
                                <div class="card-body px-0 pt-0 pb-2 d-flex mt-sm-2 mb-0">
                                    <form method="post" action="/librarianRequests/approve" class="me-2 mt-3">
                                        <input type="hidden" name="id" value="<?= $req['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="post" action="/librarianRequests/reject" class="me-2 mt-3">
                                        <input type="hidden" name="id" value="<?= $req['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </div>
                                <?php else: ?>
                                    <span class="text-secondary text-sm">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-sm">No requests found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
