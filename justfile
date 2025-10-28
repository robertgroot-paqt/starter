
import './shared/justfile'

mod backend
mod frontend

default:
    just --list

fix:
    just backend::fix frontend::fix

lint:
    just backend::lint frontend::lint