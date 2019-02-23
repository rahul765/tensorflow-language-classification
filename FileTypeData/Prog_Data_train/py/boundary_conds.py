import numpy as np

class PeriodicBC(object):
    """
    Implements periodic boundary conditions so that a pulse propagating out to the
    side pops back up on the other side.

    Currently, hard-coded for 5th order WENO.
    """
    def __init__(self):
        pass

    def extend_dx(self, dx):
        out = np.zeros(len(dx) + 4)
        out[2:-2] = dx
        out[0] = out[-3]
        out[1] = out[-3]
        out[-2] = out[2]
        out[-1] = out[2]
        return out

    def compute(self, t, now):
        now[0] = now[-4]
        now[1] = now[-3]  # np.sin(30 * t)
        now[-2] = now[2]
        now[-1] = now[3]
        return now

def test_circular_boundary_conditions():
    pbc = PeriodicBC()
    x = np.linspace(0.0, 4.9, 50) + 0.05
    added_ghosts = pbc.compute(0, x)
    np.testing.assert_almost_equal(added_ghosts[0], 4.65)
    np.testing.assert_almost_equal(added_ghosts[1], 4.75)
    np.testing.assert_almost_equal(added_ghosts[-2], 0.25)
    np.testing.assert_almost_equal(added_ghosts[-1], 0.35)
